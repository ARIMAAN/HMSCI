// ...existing code...

<script>
// Initialize global tracker instance
$(document).ready(function() {
    if (typeof AttendanceTracker !== 'undefined' && !window.globalTracker) {
        window.globalTracker = new AttendanceTracker({
            baseUrl: '<?php echo base_url();?>',
            csrfName: '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash: '<?php echo $this->security->get_csrf_hash(); ?>'
        });
    }
});
</script>
</body>
</html>