<?php

namespace App\Http\Controllers;

use App\Exports\WithdrawalReportExport;
use App\Models\Client;
use App\Models\Generator;
use App\Models\Transporter;
use App\Models\Withdrawal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    // ─── Vista + preview ──────────────────────────────────────────────────────

    public function withdrawals(Request $request)
    {
        $generators  = Generator::orderBy('company_name')->get(['id', 'company_name']);
        $transporters = Transporter::orderBy('company_name')->get(['id', 'company_name']);

        $withdrawals = null;
        $filters     = [];

        if ($request->hasAny(['date_from', 'date_to', 'generator_id', 'transporter_id', 'payment_status'])) {
            [$withdrawals, $filters] = $this->query($request);
        }

        return view('content.reports.withdrawals', compact('generators', 'transporters', 'withdrawals', 'filters'));
    }

    // ─── Exportar Excel ───────────────────────────────────────────────────────

    public function withdrawalsExcel(Request $request)
    {
        [$withdrawals] = $this->query($request);

        $filename = 'retiros_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new WithdrawalReportExport($withdrawals), $filename);
    }

    // ─── Exportar PDF ─────────────────────────────────────────────────────────

    public function withdrawalsPdf(Request $request)
    {
        [$withdrawals, $filters] = $this->query($request);

        $pdf = Pdf::loadView('content.reports.withdrawals-pdf', compact('withdrawals', 'filters'))
            ->setPaper('letter', 'landscape');

        $filename = 'retiros_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    // ─── Query compartida ─────────────────────────────────────────────────────

    private function query(Request $request): array
    {
        $query = Withdrawal::with([
            'generator',
            'subGenerator',
            'transporter',
            'manifest',
            'finalDestination',
            'items.waste',
            'user',
        ])->orderBy('reception_date', 'desc');

        $filters = [];

        if ($from = $request->input('date_from')) {
            $query->whereDate('reception_date', '>=', $from);
            $filters['date_from'] = $from;
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('reception_date', '<=', $to);
            $filters['date_to'] = $to;
        }
        if ($genId = $request->input('generator_id')) {
            $query->where('generator_id', $genId);
            $filters['generator_id'] = $genId;
            $filters['generator_name'] = Generator::find($genId)?->company_name;
        }
        if ($tranId = $request->input('transporter_id')) {
            $query->where('transporter_id', $tranId);
            $filters['transporter_id'] = $tranId;
            $filters['transporter_name'] = Transporter::find($tranId)?->company_name;
        }
        if ($status = $request->input('payment_status')) {
            $statuses = is_array($status) ? $status : [$status];
            $query->whereIn('payment_status', $statuses);
            $filters['payment_status'] = $statuses;
        }

        return [$query->get(), $filters];
    }
}
