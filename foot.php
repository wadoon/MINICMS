 </div> <!-- #content -->
<div class="footer clear">
<?=realpath($requested_file)?> <br />
Modified/Created/Current: 
<strong><?=@date('Y-M-d', filemtime($requested_file)); ?> /
<?=@date('Y-M-d', filectime($requested_file)); ?> /
<?=date('Y-M-d'); ?></strong>
</pre>
</div>
<!-- #body-->
</body>
</html>