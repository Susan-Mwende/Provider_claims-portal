function updatebox(varSerialNumber,billamt,totalcount1)
{
    document.getElementById("bankcharges").value='0.00';
    var adjamount1;
    var grandtotaladjamt2=0;
    var varSerialNumber = varSerialNumber;
    var totalcount1=document.getElementById("totcount").value;
    var billamt = billamt;
    var textbox = document.getElementById("adjamount"+varSerialNumber+"");
    textbox.value = "";
    if(document.getElementById("acknow"+varSerialNumber+"").checked == true)
    {
        if(document.getElementById("acknow"+varSerialNumber+"").checked) {
            textbox.value = billamt;
        }
        var balanceamt=billamt-billamt;
        document.getElementById("balamount"+varSerialNumber+"").value=balanceamt.toFixed(2);
        var totalbillamt=document.getElementById("paymentamount").value;
        if(totalbillamt == 0.00)
        {
            totalbillamt=0;
        }
        totalbillamt=parseFloat(totalbillamt)+parseFloat(billamt);

        totalbillamt1=totalbillamt.toFixed(2);
        totalbillamt1 = totalbillamt1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        //alert(totalbillamt);
        document.getElementById("paymentamount").value = totalbillamt.toFixed(2);
        document.getElementById("netpayable").value = totalbillamt.toFixed(2);
        document.getElementById("totaladjamt").value=totalbillamt1;
    }
    else
    {
//alert(totalcount1);
        for(j=1;j<=totalcount1;j++)
        {
            var totaladjamount2=document.getElementById("adjamount"+j+"").value;
            if(totaladjamount2 == "")
            {
                totaladjamount2=0;
            }
            grandtotaladjamt2=grandtotaladjamt2+parseFloat(totaladjamount2);
        }
//alert(grandtotaladjamt);
        document.getElementById("paymentamount").value = grandtotaladjamt2.toFixed(2);
        document.getElementById("netpayable").value = grandtotaladjamt2.toFixed(2);
        document.getElementById("totaladjamt").value=grandtotaladjamt2.toFixed(2);
    }
}
