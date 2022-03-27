<table>
    <thead>
        <tr>                            
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Kode</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Nama</b>
            </td>                      
            <td style="width:100px"
                valign="top"
                align="center">
                <b>User</b>
            </td>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td height="50px"
                align="center"
                valign="top">
                {{$item->code}} 
            </td>
            <td height="50px"
                align="center"
                valign="top">
                {{$item->name}} 
            </td>          
            <td height="50px"
                align="center"
                valign="top">
                {{$item->user ? $item->user->name ?? "-" : "-"}}
            </td>           
        </tr>
        @endforeach
    </tbody>
</table>

<style>
*{
    font-size: 12px;
}
</style>