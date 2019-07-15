const employers = document.getElementById('employers');

if (employers){
    employers.addEventListener('click', e =>{
        if (e.target.className === 'btn btn-danger delete-employer') {
            if (confirm('Voulez-vous supprimer')) {
                const id = e.target.getAttribute('data-id');

                fetch('/employer/delete/${id}', {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}