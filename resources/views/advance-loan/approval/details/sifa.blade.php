<tr>
    <th style="width:35%;">Previous Month Net Pay<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
    <td style="padding-left:25px;"> {{ formatAmount($advance->netPay) }}
 </td>
</tr>
<tr>
    <th style="width:35%;">Interest Rate<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
    <td style="padding-left:25px;"> {{$advance->interest_rate }}</td>
</tr>
<tr>
    <th style="width:35%;">No. Of EMI<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
    <td style="padding-left:25px;"> {{$advance->no_of_emi }} months</td>
</tr>
<tr>
    <th style="width:35%;">Monthly EMI Amount<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
    <td style="padding-left:25px;"> {{$advance->monthly_emi_amount }}</td>
</tr>
<tr>
    <th style="width:35%;">Deduction Period From<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
    <td style="padding-left:25px;"> {{ \Carbon\Carbon::parse($advance->deduction_from_period)->format('d-M-Y') }}</td>
</tr>
<tr>
    <th style="width:35%;">Net Payable<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
    <td style="padding-left:25px;"> {{ formatAmount($advance->net_payable )}}</td>
</tr>