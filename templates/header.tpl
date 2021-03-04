<html lang="de"> <head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <head>
    <title>Semesterapparate</title>
    <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8" />
    <meta http-equiv=“cache-control“ content=“no-cache“>
    <meta http-equiv=“pragma“ content=“no-cache“>
    <meta http-equiv=“expires“ content=“0″>
    <link   type="text/css"        href="lib/style.css"     rel="stylesheet"  />
    <link   type="text/css"        href="lib/jquery-ui.css" rel="stylesheet" />
    <link   href="https://fonts.googleapis.com/css?family=Fira+Sans|Open+Sans&display=swap" rel="stylesheet">
    <script type="text/javascript" src="lib/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="lib/jquery-ui.js"></script>
    <script type="text/javascript" src="lib/else.js"></script>
  </head>

  {if  $user.role_name == "admin" ||  $user.role_name == "staff"}
<body style='margin:0px; padding:0; padding-left:calc(50% - 400px);' >
{else}
<body style='margin:0px; padding:0px;' >
{/if}
{* $user, $operator, $filter, $semester *}

{if  $user.role_name == "admin" ||  $user.role_name == "staff"}

{if  $operator.mode == 'print'}{* No Header *}
{else}
<div id="{$medium.id}"; style="position:relative;  padding:0px; height:38px; margin:0px; margin-bottom:20px; margin-left:4px; margin-right: 0px;  padding-left: 55px;">

<!---->
  <div style="position:relative;  left:0px; top:0px; height:40px; padding-right: 3px;"  >
    <ul id="nav" style="position:absolute; left:5px;   top:3px; margin-right:60px; border: white solid 2px;"   >
      <li><a title="Alle Semesterapparate"          class="bgHAW2x"  {if $filter.bib == X                            } style=" background-color:#FFF !important; color:#000 !important;  " {/if}  href="index.php?category=X&amp;mode=filterBib&amp;r={$user.role_id}"> X  </a></li>
      <li><a title="Semesterapparate der FB DMI"    class="bgHAW2"   {if $filter.bib == DMI                          } style=" background-color:#FFF !important; color:#000 !important;  " {/if}  href="index.php?category=DMI&amp;mode=filterBib&amp;r={$user.role_id}">  DMI  </a></li>
      <li><a title="Semesterapparate der FB LS"     class="bgHAW2"   {if $filter.bib == LS                           } style=" background-color:#FFF !important; color:#000 !important;  " {/if}  href="index.php?category=LS&amp;mode=filterBib&amp;r={$user.role_id}">   LS   </a></li>
      <li><a title="Semesterapparate der FB SP"     class="bgHAW2"   {if $filter.bib == SP                           } style=" background-color:#FFF !important; color:#000 !important;  " {/if}  href="index.php?category=SP&amp;mode=filterBib&amp;r={$user.role_id}">   SP   </a></li>
      <li><a title="Semesterapparate der FB TWI1"   class="bgHAW2"   {if $filter.bib == TWI1                         } style=" background-color:#FFF !important; color:#000 !important;  " {/if}  href="index.php?category=TWI1&amp;mode=filterBib&amp;r={$user.role_id}"> TWI1 </a></li>
      <li><a title="Semesterapparate der FB TI"     class="bgHAW2"   {if $filter.bib == TWI2                         } style=" background-color:#FFF !important; color:#000 !important;  " {/if}  href="index.php?category=TWI2&amp;mode=filterBib&amp;r={$user.role_id}"> TWI2 </a></li>
      <li><a title="Semesterapparate ohne Fakultät" class="bgHAW2x"  {if $filter.bib == OHNE  OR  $filter.bib == HAW } style=" background-color:#FFF !important; color:#000 !important;  " {/if}  href="index.php?category=HAW&amp;mode=filterBib&amp;r={$user.role_id}">  0    </a></li>
    </ul>
  </div>

{if  ($SEMESTER != null) }
  <ul id="nav" style="position:absolute; left:350px;   top:2px; margin-right:10px; border: white solid 2px;">
    <li><a class="en" title="Alle Semester" href="index.php?category=X&mode=filterSem&r=2"    {if $filter.sem == X   } style="background-color:#FFF; color:#000;" {/if}>X</a></li>
      {foreach from=$SEMESTER item=sem}
        <li><a class="enS" title="Semester {$sem}"  href="index.php?category={$sem}&mode=filterSem&r=2" {if  $filter.sem == $sem } style="background-color:#FFF; color:#000;" {/if}>{$sem}</a></li>
      {/foreach}
  </ul>
{/if}

<ul id="nav2" style="position:absolute; right:150px;   top:3px;">
    {if      $filter.state ==  0 AND $filter.type != 16}<li><a class="bo"  href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=0"  title="Alle SemApp"         ><img src="img/svg/Xa.svg" width="32" height="32"/></a>
    {elseif  $filter.state ==  1}                       <li><a class="bo"  href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=1"  title="Neu Bestellte"       ><img src="img/svg/Na.svg" width="32" height="32"/></a>
    {elseif  $filter.state ==  2}                       <li><a class="bo"  href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=2"  title="Wird Bearbeitet"     ><img src="img/svg/Ba.svg" width="32" height="32"/></a>
    {elseif  $filter.state ==  3}                       <li><a class="bo"  href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=3"  title="Aktiv"               ><img src="img/svg/Aa.svg" width="32" height="32"/></a>
    {elseif  $filter.state ==  4}                       <li><a class="bo"  href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=4"  title="Wird Entfernt"       ><img src="img/svg/Ea.svg" width="32" height="32"/></a>
    {elseif  $filter.state ==  5}                       <li><a class="bo"  href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=5"  title="Inaktiv"             ><img src="img/svg/Ia.svg" width="32" height="32"/></a>
    {elseif  $filter.state ==  6}                       <li><a class="bo"  href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=6"  title="Gelöschte"           ><img src="img/svg/Ga.svg" width="32" height="32"/></a>
    {elseif  $filter.state == 10}                       <li><a class="bo"  href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=10" title="Verlängerte"         ><img src="img/svg/Va.svg" width="32" height="32"/></a>
    {elseif  $filter.type  == 16}                       <li><a class="bo"  href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=16"  title="Erwerbungsvorschlag" ><img src="img/svg/Ka.svg" width="32" height="32"/></a>
    {/if}

    <ul>
      <li><a href='index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=0' title="Alle Medien"         ><img src="img/svg/Xa.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Alle Medien        </span></a></li>
      <li><a href='index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=1' title="Neu Bestellt"        ><img src="img/svg/Na.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Neu bestellte M.   </span></a></li>
      <li><a href='index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=2' title="Wird Bearbeitet"     ><img src="img/svg/Ba.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Zu bearbeitende    </span></a></li>
      <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=16" title="Erwerbungsvorschlag" ><img src="img/svg/Ka.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Erwerbungsvorsch.  </span></a></li>
      <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=3" title="Aktiv"               ><img src="img/svg/Aa.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Aktive M.          </span></a></li>
      <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=5" title="Inaktiv"             ><img src="img/svg/Ia.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Inaktive M.        </span></a></li>
      <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=4" title="Entfernte"           ><img src="img/svg/Ea.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Zu entfernende M.  </span></a></li>
      <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=6" title="Gelöschte"           ><img src="img/svg/Ga.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Gelöschte M.       </span></a></li>
    </ul>
  </li>
</ul>

<div  style="position:absolute; right:77px;   top:3px;">
  <a href="javascript:window.print() " title="Druckversion Seite" >  <img src="img/svg/print.svg"   width="32"  height="32"   /></a>
</div>

  <ul id="nav2" style="position:absolute; right:36px;   top:3px;">
    <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=0" title="Alle SemApp"         ><img src="img/svg/settings.svg" width="32" height="32"/></a>
     <ul>
        <li><a href='../php/editconf.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=0' title="Alle Semapp"         ><img src="img/svg/edit_w.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> EDIT CONFIG    </span></a></li>
      </ul>
    </li>
  </ul>


<div style="position:absolute; right:2px;   top:3px;">
  <a href='index.php' title="HOME" ><img src="img/svg/home_b.svg" width="32" height="32"/></a>
</div>
</div>

{/if}

{/if}