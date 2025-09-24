@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tüm Faturalar</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fatura No</th>
                                    <th>Hasta</th>
                                    <th>Tarih</th>
                                    <th>Vade Tarihi</th>
                                    <th>Toplam Tutar</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_no }}</td>
                                    <td>{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</td>
                                    <td>{{ $invoice->issue_date->format('d.m.Y') }}</td>
                                    <td>{{ $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '-' }}</td>
                                    <td>{{ number_format($invoice->grand_total, 2) }} TL</td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->status->name === 'PAID' ? 'success' : ($invoice->status->name === 'UNPAID' ? 'danger' : 'warning') }}">
                                            {{ $invoice->status->value }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('accounting.invoices.action', $invoice) }}" class="btn btn-sm btn-info">Düzenle</a>
                                        <form action="{{ route('accounting.invoices.destroy', $invoice) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu faturayı çöp kutusuna taşımak istediğinizden emin misiniz?')">Sil</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">Henüz hiç fatura bulunmamaktadır.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
