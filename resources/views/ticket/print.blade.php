<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Print Ticket</title>
		<script src=""></script>
		
<style type="text/css" media="all">
	body { 
		max-width: 300px; 
		margin:0 auto; 
		text-align:center; 
		color:#000; 
		font-family: Arial, Helvetica, sans-serif; 
		font-size:12px; 
	}
	#wrapper { 
		min-width: 250px; 
		margin: 0px auto; 
	}
	#wrapper img { 
		max-width: 300px; 
		width: auto; 
	}

	h2, h3, p { 
		margin: 5px 0;
	}
	.left { 
		width:100%; 
		float:right; 
		text-align:right; 
		margin-bottom: 3px;
		margin-top: 3px;
	}
	.right { 
		width:40%; 
		float:right; 
		text-align:right; 
		margin-bottom: 3px; 
	}
	.table, .totals { 
		width: 100%; 
		margin:10px 0; 
	}
	.table th { 
		border-top: 1px solid #000; 
		border-bottom: 1px solid #000; 
		padding-top: 4px;
		padding-bottom: 4px;
	}
	.table td { 
		padding:0; 
	}
	.totals td { 
		width: 24%; 
		padding:0; 
	}
	.table td:nth-child(2) { 
		overflow:hidden; 
	}

	@media print {
		
		#buttons { display: none; }
		#wrapper { width: 95%; font-size:12px; }
		#wrapper img { max-width:300px; width: 80%; }
		#bkpos_wrp{
			display: none;
		}
	}
</style>
</head>

<body onload="window.print();">
<div id="wrapper">
	<table border="0" style="border-collapse: collapse; width: 100%; height: auto;">
	    <tr>
		    <td width="100%" align="center">
			    <center>
			    	<img src="{{ $tourismInfo->url_logo }}" style="width: 100px;" />
			    </center>
		    </td>
	    </tr>
	    <tr>
		    <td width="100%" align="center">
				<h2 style="padding-top: 0px; font-size: 20px;"><strong>{{ $tourismInfo->name }}</strong></h2>
			    <h4 style="margin: 0px; font-size: 12px;"><strong>{{ $tourismInfo->manage_by }}</strong></h4>				
            </td>
            
        </tr>
        <tr>
			<td width="100%">
				<span class="left" style="text-align: center;">{{ $tourismInfo->address }}</span>	
			</td>
		</tr>  
		<tr>
		</tr>   
    </table>

    <div style="clear:both;"></div>
    
	<table class="table" cellspacing="0"  border="0"> 
		<thead> 
			<tr> 
				<th width="10%">Kode</th> 
				<th width="25%" align="right">Harga</th>
			</tr> 
		</thead> 
		<tbody> 
            @foreach ($ticketShowPrints as $ticketShowPrint)
            <tr>
                <td style="text-align:center; width:30px;" valign="top">{{ $ticketShowPrint->code }}</td>
                <td style="text-align:right; width:70px;" valign="top">{{ number_format($ticketShowPrint->price) }}</td>
            </tr>
            @endforeach
            
    	</tbody> 
	</table>   
	
	    
    <div style="clear:both;"></div>
    
    <table class="table" cellspacing="0"  border="0"> 
		<thead> 
			<tr> 
				<th width="10%">Total</th> 
				<th width="25%" align="right">{{ number_format($ticketTotalPrice) }}</th>
			</tr>
		</thead> 
	</table>  

	<table border="0" style="border-collapse: collapse; width: 100%; height: auto;">
		@if ($tourismInfo->insurance != NULL)
		<tr >
			<td width="100%" colspan="2">
				<span class="left" style="text-align: center;">Termasuk Asuransi {{ $tourismInfo->insurance }}</span>	
			</td>
			<td>

			</td>
		</tr>  
		@endif
		@if ($ticketPromotion != NULL)
			<tr > 
				<td width="100%" colspan="2">{{ $ticketPromotion->name }}</td> 
				<td></td>
			</tr> 
		@endif
		<tr>
			<td><br/></td>
			<td><br/></td>
		</tr>		
	    <tr>
		    <td align="left">
			 
			    	<img src="{{ asset('assets/images/master/pemda.png') }}" style=" width:30px;" />			    	
			  
			</td>
			<td align="right">
					<img src="{{ asset('assets/images/master/wonderful_indonesia_logo.webp') }}" style="width: 100px;" />
				
			</td>
	    </tr> 
		<tr>
		</tr>   
    </table>
    	
    </div>

    <div id="bkpos_wrp">
    	<a href="" style="width:100%; display:block; font-size:12px; text-decoration: none; text-align:center; color:#FFF; background-color:#005b8a; border:0px solid #007FFF; padding: 10px 1px; margin: 5px auto 10px auto; font-weight:bold;">Back to Ticket</a>
    </div>
    
	
    <div id="bkpos_wrp">
    	<button type="button" onClick="window.print();return false;" style="width:101%; cursor:pointer; font-size:12px; background-color:#FFA93C; color:#000; text-align: center; border:1px solid #FFA93C; padding: 10px 0px; font-weight:bold;">Print</button>
    </div>
    

    
    
    
</div>

</body>
</html>
