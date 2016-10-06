<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <table id="data-table" class="table table-striped table-bordered">
<thead> 
    <tr> 
    <th>ID</th>
    <th>Name</th>  
    <th>Father's Mobile</th>
    <th>Mother's Mobile</th>
    <th>Total Amount</th>
    <th>Balance</th>
    </tr>
</thead>
@foreach($students as $student)
@if($student->studentBalance)
<tr>  
<td>{{$student->student_id}}</td>
<td>{{$student->full_name}}</td> 
<td>{{$student->father_mob}}</td>
<td>{{$student->mother_mob}}</td>
<td>{{$student->totalPayable}}</td>
<td>{{$student->studentBalance}}</td>
</tr>
@endif
@endforeach

<tbody>  
<tfoot><tr><td colspan="5">&nbsp;</td><td><strong>{{ $gradeBalance }}</strong></td></tr></tfoot>
</table>
 