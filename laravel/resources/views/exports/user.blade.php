<table>
    <thead>
        <tr>                            
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Kode</b>
            </td>    
        </tr>
        <tr>                            
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Username</b>
            </td>    
        </tr>
        <tr>                            
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Nama</b>
            </td>    
        </tr>
         <tr>                            
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Email</b>
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
                {{$item->username}} 
            </td>     
            <td height="50px"
                align="center"
                valign="top">
                {{$item->name}} 
            </td>          
             <td height="50px"
                align="center"
                valign="top">
                {{$item->email}} 
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