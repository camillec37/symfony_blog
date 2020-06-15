$(function(){ //DOM ready
    $('.btn-content').click(function(event){
        //évite d'aller sur cette page
        event.preventDefault();

        //objet jquery sur le lien cliqué
        var $btn = $(this);

        //appel ajax en GET
        $.get(
            //page appelé en ajax : page dont l'url est dans l'attribut href du lien
            $btn.attr('href'),
            //fonction de callback qui traite la réponse
            function(response) {
                var $modal = $('#modal-content');

                //intégration du contenu de la modale dans la div modal-body
                $modal.find('.modal-body').html(response);

                //affiche la modale
                $modal.modal('show');
            }
        );
    });
});