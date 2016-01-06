<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <table>
    <thead>
        <tr>
        <th>#</th>
        <th>ID</th>
        <th>Student</th> 
        <th>Grade</th>                                    
        <th>Subscription</th>
        <th>Validity</th>
        <th>Transportation</th>
        <th>Discount</th>
        <th>Discount Reason</th>
        <th>Amount </th> 
        </tr>
    </thead>
    <tbody> 
        <?php $i=1; ?>
         @foreach($subscriptions as $subs)

        <tr @if($subs->remaining_days<8) style="color:#F00" @endif>
            <td>{!! $i !!}</td>
            <td>{!! $subs->student_id !!}</td>
            <td>{!!  $subs->full_name !!}</td>
            <td>{!! $subs->standard !!}</td>
            <td>{!! $subs->group_name !!}</td>
            <td>{!!  $subs->subscription_type==5 ? date("d-M-y",strtotime($subs->start_date)) : date("d-M-y",strtotime($subs->start_date))." To ".date("d-M-y",strtotime($subs->end_date)) !!}</td>
            <td>@if($subs->added)
                    Added
                    @elseif($subs->trans==3)
                    NO
                    @elseif($subs->trans==2)
                    Oneway
                    @elseif($subs->trans==1)
                    RoundTrip
                    @endif
            </td>
            <td>{!! $subs->discount  !!}</td>
            <td>{!! $subs->discount_reason  !!}</td>
            <td>{!! $subs->amount !!}</td> 
        </tr>
        <?php $i++; ?>
    @endforeach
         
    </tbody>
</table>
                             