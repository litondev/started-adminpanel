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
                <b>Pelanggan</b>
            </td>                      
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Total</b>
            </td>
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Jumlah</b>
            </td>
              <td style="width:100px"
                valign="top"
                align="center">
                <b>Grand Total</b>
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
                Pelanggan : {{$item->customer ? $item->customer->name ?? "-" : "-"}} <br/>
                User : {{$item->user ? $item->user->name ?? "-" : "-"}}
            </td>          
            <td height="50px"
                align="center"
                valign="top">
                {{$item->total}}
            </td>           
            <td height="50px"
                align="center"
                valign="top">
                {{$item->quantity}}
            </td>       
             <td height="50px"
                align="center"
                valign="top">
                {{$item->grand_total}}
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