<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
    <thead> 
        <tr> 
        <th>Id</th>
        <th>Name</th> 
        <th>Age</th>
        <th>Gender</th>                                   
        <th>Nationality</th>                              
        <th>Father's Mobile</th>                                     
        <th>Mother's Mobile</th>
        @if($filter=='active')<th>EndDate</th><th>Remaining Days</th>@endif
        <th >Balance</th> 
        </tr>
    </thead> 
     <tbody> 
    @foreach($students as $student) 
    <tr>    
    <td>{{$student->student_id}}</td>
    <td>{{$student->full_name}}</td> 
    <td>{{round($student->age)}}</td>
    <td >{{ ($student->gender=='f')?"Girl" : "Boy" }} </td>
    <td>{{$student->nation}}</td>
    <td>{{$student->father_mob}}</td>
    <td>{{$student->mother_mob}}</td>
    @if($filter=='active')<td>{{$student->end_date}}</td><td>{{$student->remainingDays}}</td>@endif
    <td >{{ ($student->totalSubs+$student->totalRefunded+$student->totalHours)-$student->totalPaid }}</td>
    </tr>
    @endforeach
  
   
    </tbody>
    
</table>