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

{foreach key=key item=c name=collectionList from=$collectionList }
  {if $c != 0}
    <div class="dozentName bgDef bg{$c[0].Owner.bib_id}">                                                                {*  <!-- HEADLINE:  DOZENT  -->   *}
      <a class="dozentLink" href="#">{* <!-- Doz.ID -. *} {$c[0].Owner.forename} {$c[0].Owner.surname}   </a>
      <span style="float:right">{$c[0].Owner.dep_name}</span>{*  <!-- Department -.  <!-- Fak.ID, Dozent Titel,  Vorname, Nachname -->   *}
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
          <div class="name2 semapNameListe"  >{$c[j].title}</div>
          <div class="name  semapNameListe semapNameListeNumbers "  style="background: url(img/bg/{$c[j].bib_id}.png);">({$c[j].MedState.med_state_GE})<br/>{$c[j].bib_id}</div>
          </a>
        </div>
    {/if}
    {/section}
  {/if}
{/foreach}


