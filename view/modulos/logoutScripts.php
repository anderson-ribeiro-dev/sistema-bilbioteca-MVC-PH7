<script>
    $(document).ready(function() {
        $('.btn-exit-system').on('click', function(e) {
            e.preventDefault();
            var Token = $(this).attr('href'); //get token form
            swal({
                title: 'Têm Certeza?',
                text: "Sessão Atual Será Encerrada!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#03A9F4',
                cancelButtonColor: '#F44336',
                confirmButtonText: '<i class="zmdi zmdi-run"></i> Sim, Encerrar!',
                cancelButtonText: '<i class="zmdi zmdi-close-circle"></i> Não, Cancelar!'
            }).then(function() {
                $.ajax({
                    url: '<?php echo SERVERURL; ?>/ajax/loginAjax.php?Token=' + Token,
                    success: function(data) {
                        console.log(data);
                        if (data === "true") {
                            window.location.href = "<?php echo SERVERURL; ?>/login";
                        } else {
                            swal(
                                "Ocorreu um erro!",
                                "A sessão não pode ser encerrada!",
                                "error"
                            );
                        }
                    }
                });
            });
        });
    });
</script>