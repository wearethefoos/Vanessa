<script language="javascript">
document.observe('dom:loaded', function() {
   <?php if (!isset($class)) $class='default-'; ?>
   if ($('flashMessage')) {
     $('flashMessage').addClassName('<?php echo $class ?>');
     $('flashMessage').update('<?php echo $message; ?>');
     $('flashMessage').morph('top:0px;', { duration:1.3, transition: 'elastic' });
     setTimeout(function() {
       $('flashMessage').morph('top:-100px;', { duration:1.3, transition: 'easeTo' });
     },
     10000);
   }
});
</script>

<style>
<!--
 .modal {
   position: fixed;
   top: -100px;
   left: 0;
   width: 100%;
   padding: 20px 250px;
   margin: 0;
  }

  .success {
    background: #cfc;
    border-bottom: 3px solid #090;
    color:#090;
  }

  .error {
    background: #fcc;
    border-bottom: 3px solid #c06;
    color:#c06;
  }
  
  .default- {
    background: #cff;
    border-bottom: 3px solid #9ff;
    color:#9ff;
  }
-->
</style>
