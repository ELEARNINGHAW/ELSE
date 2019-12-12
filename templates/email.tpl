<h3 style="margin:10px; padding:10px; color: #FFF; background-color: #800000;">
{$collection.title} : {$collection.media[ 0 ].title } : Email
<a style="float:right;" href="{$operator.history.1}"> <img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left_w.svg" /></a>
</h3>
  

{if $operator.msg == ''}
<br />
<form  action="index.php" >

<input  style="float:right;width:125px; height:50px;" name="send"  value="Senden" type="submit" />

<span  style="margin:10px; font-family:Arial, Helvetica, sans-serif;" >An: {$collection.Owner.forename} {$collection.Owner.surname} &lt;{$collection.Owner.email}&gt; </span><br/><br/>
<span  style="margin:10px; font-family:Arial, Helvetica, sans-serif;" >Betreff: Ihr ELSE Semesterapparat </span>



<input  name="to"                 type="hidden"  value="{$collection.Owner.email}"/>
<input  name="from"               type="hidden"  value="{$user.email}"/>
<input  name="document_id"        type="hidden"  value="{$medium.id}"/>
<input  name="dc_collection_id"   type="hidden"  value="{$collection.dc_collection_id}"/>
<input  name="item"               type="hidden"  value="email"/>
<input  name="action"             type="hidden"  value="sendmail"/>

<br />
<textarea  style="margin:10px; width:95%" name="mailtext" cols="60" rows="18">{$salutaton}

Ihr Semesterapparat: {$collection.title }

Ihr Buch/Medium: {$collection.media[ 0 ].title }


{$collection.media[ 0 ].notes_to_staff}
 

Mit freundlichen Gr&uuml;&szlig;en
{$user.forename} {$user.surname}

HIBS-Serviceteam 
{$user.email}



</textarea>


</form>

{else}
  <br />
  <br />
  <br />
  <br />
  <br />
  
  <h1 style="text-align: center"><a style="text-decoration: none;" href="{$operator.url}">{$operator.msg}</a></h1>
  
  
{/if}


</body>
</html>
