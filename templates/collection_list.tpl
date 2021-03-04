{* $operator, $filter, $collectionList  *}
{if $filter.sem == 'X'}{$filter.sem = " Alle Semester "}{/if}
  
{if      $filter.bib == 'X'   }  <div class="depName bg{$filter.bib}" >  Semesterapparate der gesamten HAW                  : {$filter.sem} </div>
{elseif  $filter.bib == 'DMI'
      or $filter.bib == 'LS'
      or $filter.bib == 'TWI1'
      or $filter.bib == 'TWI2'
      or $filter.bib == 'SP'  }  <div class="depName bg{$filter.bib}" >  Semesterapparate der Fachbibliothek {$filter.bib} : {$filter.sem} </div>
{elseif  $filter.bib == 'HAW' }  <div class="depName bgDef"           >  Semesterapparate ohne Fakultät                    : {$filter.sem} </div>
{else}                           <div class="depName bg{$filter.bib}" >  Semesterapparate                                                  </div>
{/if}

<div style="position: absolute; display: block; background-color: #FBFBFB; padding-right:50px; padding-left:50px">
<div style="position: relative ; display: block; background-color: #9B410E; width:790px;">

  {foreach key=key item=c name=collectionList from=$collectionList }
  {if $c != 0}

    <div class="dozentName" >                                                                {*  <!-- HEADLINE:  DOZENT  -->   *}
      <a class="dozentLink" href="#">{* <!-- Doz.ID -. *} {$c[0].Owner.forename} {$c[0].Owner.surname}   </a>
          <span class="fachbibName  c{$c[0].Owner.bib_id}"   style="float:right">{$c[0].Owner.dep_name}</span>{*  <!-- Department -.  <!-- Fak.ID, Dozent Titel,  Vorname, Nachname -->   *}
    </div>

    {section name=j loop=$c}
    {if ( ( $operator.mode == "edit"  AND $c[j].state_name != 'delete') OR  ($operator.mode == "admin" or $operator.mode == "staff") ) AND $c[0].bib_id != 0  }{*  <!-- HEADLINE:  SEMAPP -- EDITMODE  -. *}
      <a class="name lb{$c[j].bib_id|escape} semapNameListe  href="index.php?item=collection&collection_id={$c[j].id}&item=collection&action=show_collection&amp;r={$c[0].Owner.role_id }">{$c[j].title|escape} </a>

      {if $operator.mode == "edit" or $operator.mode == "staff" or $operator.mode == "admin"}
        <div  class="semapIconListe"> {include file="action_button_bar.tpl" mode=$operator.mode item="collection" state=$c[j].state_name collection_id=$c[j].id  document_id=0 } </div>
      {/if}

      {elseif $c[j].state_name != 'delete'} {* HEADLINE:  SEMAPP -- USER-MODE  *}
        <div class='SAHeadline' style="display: block;" >
          <a href="index.php?item=collection&action=show_collection&dc_collection_id={$c[j].dc_collection_id}&amp;r={$user.role_id}">
          <div class="name2 semapNameListe"  >{$c[j].title|truncate:85:"...":true}</div>
          <div class="name  semapNameListeNumbers bgc{$c[j].bib_id} ">[{$c[j].MedState.med_state_GE}] / {$c[j].bib_id}</div>
          </a>
        </div>
    {/if}
    {/section}
  {/if}
{/foreach}

</div>
</div>