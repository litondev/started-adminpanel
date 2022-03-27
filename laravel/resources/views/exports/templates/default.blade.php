<table>
    <thead>
        <tr>
            @foreach($data as $item)
                <td style="width:250px;{{$item['required'] ? 'color:red' : 'color:black'}}"
                    valign="top"
                    align="center">
                    <b>{{$item['name']}}</b>
                </td>                                    
            @endforeach
        </tr>
    </thead>

    <tbody>        
        <tr>
        @foreach($values as $item)                
            <td style="color:blue">
               <b>*Ex : {{$item["value"]}} </b>
            </td>
        @endforeach
        </tr>
    </tbody>
</table>