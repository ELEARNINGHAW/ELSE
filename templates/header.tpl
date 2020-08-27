<html lang="de"> <head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <head>
    <title>Semesterapparate</title>
    <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8" />
    <link   type="text/css"        href="lib/style.css"     rel="stylesheet"  />
    <link   type="text/css"        href="lib/jquery-ui.css" rel="stylesheet" />
    <link   href="https://fonts.googleapis.com/css?family=Fira+Sans|Open+Sans&display=swap" rel="stylesheet">
    <script type="text/javascript" src="lib/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="lib/jquery-ui.js"></script>
    <script type="text/javascript" src="lib/else.js"></script>
  </head>

  {if  $user.role_name == "admin" ||  $user.role_name == "staff"}
<body style='margin:0px; padding:0px;' >
{else}
<body style='margin:0px; padding:0px;' >
{/if}


{* $user, $operator, $filter, $semester *}

{if  $user.role_name == "admin" ||  $user.role_name == "staff"}

{if  $operator.mode == 'print'}{* No Header *}
{else}

<div id="{$medium.id}"; style="position:relative;  padding:0px; height:38px; margin:0px;  background-color: #234A89;  margin-bottom:20px;  margin-left:0px; margin-right: 0px;">
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
  <ul id="nav" style="position:absolute; left:300px;   top:2px; margin-right:10px; border: white solid 2px;">
    <li><a class="en" title="Alle Semester" href="index.php?category=X&mode=filterSem&r=2"    {if $filter.sem == X   } style="background-color:#FFF; color:#000;" {/if}>X</a></li>
      {foreach from=$SEMESTER item=sem}
        <li><a class="enS" title="Semester {$sem}"  href="index.php?category={$sem}&mode=filterSem&r=2" {if  $filter.sem == $sem } style="background-color:#FFF; color:#000;" {/if}>{$sem}</a></li>
      {/foreach}
  </ul>
{/if}


<ul id="nav2" style="position:absolute; right:110px;   top:3px;">
    {if      $filter.state == 0 AND $filter.type != 16}<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=0" title="Alle SemApp"         ><img src="img/svg/Xa.svg" width="32" height="32"/></a>
    {elseif  $filter.state == 1}                       <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=1" title="Neu Bestellte"       ><img src="img/svg/Na.svg" width="32" height="32"/></a>
    {elseif  $filter.state == 2}                       <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=2" title="Wird Bearbeitet"     ><img src="img/svg/Ba.svg" width="32" height="32"/></a>
    {elseif  $filter.state == 3}                       <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=3" title="Aktiv"               ><img src="img/svg/Aa.svg" width="32" height="32"/></a>
    {elseif  $filter.state == 4}                       <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=4" title="Wird Entfernt"       ><img src="img/svg/Ea.svg" width="32" height="32"/></a>
    {elseif  $filter.state == 5}                       <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=5" title="Inaktiv"             ><img src="img/svg/Ia.svg" width="32" height="32"/></a>
    {elseif  $filter.state == 6}                       <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=6" title="Gelöschte"           ><img src="img/svg/Ga.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 16}                       <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=16" title="Erwerbungsvorschlag" ><img src="img/svg/K.svg" width="32" height="32"/></a>
    {/if}

    <ul>
      <li><a href='index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=0' title="Alle Medien"         ><img src="img/svg/X.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Alle Medien        </span></a></li>
      <li><a href='index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=1' title="Neu Bestellt"        ><img src="img/svg/N.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Neu bestellte M.   </span></a></li>
      <li><a href='index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=2' title="Wird Bearbeitet"     ><img src="img/svg/B.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Zu bearbeitende    </span></a></li>
      <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=16" title="Erwerbungsvorschlag" ><img src="img/svg/K.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Erwerbungsvorsch.  </span></a></li>
      <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=3" title="Aktiv"               ><img src="img/svg/A.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Aktive M.          </span></a></li>
      <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=5" title="Inaktiv"             ><img src="img/svg/I.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Inaktive M.        </span></a></li>
      <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=4" title="Wird Entfernt"       ><img src="img/svg/E.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Zu löschende M.    </span></a></li>
      <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=6" title="Gelöschte"           ><img src="img/svg/G.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Gelöschte M.       </span></a></li>
    </ul>
  </li>
</ul>

{*
  <ul id="nav2" style="position:absolute; right:110px;   top:3px;">
    {if      $filter.type == 0 }<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=0"  title="Alle Medien"         ><img src="img/svg/Xa.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 6 }<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=6"  title="Artikel"             ><img src="img/svg/Ar.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 4 }<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=4"  title="E-Book"              ><img src="img/svg/EB.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 1 }<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=1"  title="Buch"                ><img src="img/svg/Bu.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 3 }<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=3"  title="Film"                ><img src="img/svg/Fi.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 7 }<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=7"  title="E-Artikel"           ><img src="img/svg/EAr.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 8 }<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=8"  title="E-Zeitschrift"       ><img src="img/svg/EZ.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 9 }<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=9"  title="Manuskript"          ><img src="img/svg/Ma.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 10}<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=10" title="Karte"               ><img src="img/svg/Kar.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 11}<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=11" title="Partitur"            ><img src="img/svg/Pa.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 12}<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=12" title="Zeitschrift"         ><img src="img/svg/Ze.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 13}<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=13" title="Reihe"               ><img src="img/svg/Re.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 14}<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=14" title="unbekannt"           ><img src="img/svg/Un.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 15}<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=15" title="Schriftenreihe"      ><img src="img/svg/SR.svg" width="32" height="32"/></a>
    {elseif  $filter.type == 16}<li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=16" title="Erwerbungsvorschlag" ><img src="img/svg/EV.svg" width="32" height="32"/></a>
    {/if}

     <ul>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=0"  title="Alle Medien"         ><img src="img/svg/Xa.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Alle Medien          </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=6"  title="Artikel"             ><img src="img/svg/Ar.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Artikel              </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=4"  title="E-Book"              ><img src="img/svg/EB.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> E-Book               </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=1"  title="Buch"                ><img src="img/svg/Bu.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Buch                 </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=3"  title="Film"                ><img src="img/svg/Fi.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Film                 </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=7"  title="E-Artikel"           ><img src="img/svg/EAr.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> E-Artikel            </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=8"  title="E-Zeitschrift"       ><img src="img/svg/EZ.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> E-Zeitschrift        </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=9"  title="Manuskript"          ><img src="img/svg/Ma.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Manuskript           </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=10" title="Karte"               ><img src="img/svg/Kar.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Karte                </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=11" title="Partitur"            ><img src="img/svg/Pa.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Partitur             </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=12" title="Zeitschrift"         ><img src="img/svg/Ze.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Zeitschrift          </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=13" title="Reihe"               ><img src="img/svg/Re.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Reihe                </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=14" title="unbekannt"           ><img src="img/svg/Un.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> unbekannt            </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=15" title="Schriftenreihe"      ><img src="img/svg/SR.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Schriftenreihe       </span></a></li>
        <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterType&amp;category=16" title="Erwerbungsvorschlag" ><img src="img/svg/EV.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> Erwerbungsvorschlag  </span></a></li>
      </ul>
    </li>
  </ul>
*}

<div  style="position:absolute; right:77px;   top:3px;">
  <a href="javascript:window.print() " title="Druckversion Seite" >  <img src="img/svg/print_w.svg"   width="32"  height="32"   /></a>
</div>

  <ul id="nav2" style="position:absolute; right:36px;   top:3px;">
    <li><a href="index.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=0" title="Alle SemApp"         ><img src="img/svg/edit_w.svg" width="32" height="32"/></a>
     <ul>
        <li><a href='../php/editconf.php?item=collection&amp;action=show_media_list&amp;mode=filterState&amp;category=0' title="Alle Semapp"         ><img src="img/svg/edit_w.svg" width="24" height="24"/><span style="position:absolute; padding:12px;"> EDIT CONFIG    </span></a></li>
      </ul>
    </li>
  </ul>

<div  style="position:absolute; right:2px;   top:3px;">
  <a href='index.php' title="HOME" ><img src="img/svg/home.svg" width="32" height="32"/></a>
</div>
</div>

{/if}

{/if}