{* $operator *}
{if $operator.mode != "print"}
{if $operator.action == "show_collection" OR $operator.action == "show_media_list"}
  <script src="lib/jquery.modal.min.js"></script>
  <link  href="lib/jquery.modal.min.css" rel="stylesheet" />

    <script type="text/javascript">
        $.modal.defaults = {   closeExisting: true,
            escapeClose: true,
            clickClose: true,
            closeText: 'Close',
            showClose: false,
            fadeDelay: 1.0
        };
    </script>

  <div id="helpit" class="modal">
    <p class="closeBtn" style="float:right;" > <a href="#" rel="modal:close"><img  class="icon" title="close" src="img/svg/chevron-left.svg" /></a></p>
     <iframe style="width:100%; height:300px;   z-index: 8001; " src="lib/hilfe2.html"></iframe>
  </div>
{/if}


</div>
</body>
</html>
{/if}
