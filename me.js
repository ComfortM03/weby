document.getElementById('addPersonBtn').addEventListener('click', function() {
    const name = document.getElementById('personName').value;
    const email = document.getElementById('personEmail').value;
    const role = document.getElementById('personRole').value;

    if (name && email && role) {
        // AJAX request to backend to add a new person
        fetch('/addPerson', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, email, role })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('userFeedback').innerHTML = `Person added: ${data.name}`;
        });
    } else {
        alert('Please fill in all fields');
    }
});

document.getElementById('editPersonBtn').addEventListener('click', function() {
    const id = document.getElementById('personIdEdit').value;
    const name = document.getElementById('personNameEdit').value;
    const email = document.getElementById('personEmailEdit').value;
    const role = document.getElementById('personRoleEdit').value;

    if (id && (name || email || role)) {
        // AJAX request to backend to edit person details
        fetch(`/editPerson/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, email, role })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('userFeedback').innerHTML = `Person updated: ${data.name}`;
        });
    } else {
        alert('Please fill in the necessary fields');
    }
});

document.getElementById('deletePersonBtn').addEventListener('click', function() {
    const id = document.getElementById('personIdDelete').value;

    if (id) {
        // AJAX request to backend to delete person
        fetch(`/deletePerson/${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('userFeedback').innerHTML = `Person deleted: ${data.name}`;
        });
    } else {
        alert('Please provide a person ID');
    }
});

document.getElementById('applyUpdateBtn').addEventListener('click', function() {
    const scope = document.getElementById('updateScope').value;
    // Handle logic to show update globally or per user
    if (scope === 'global') {
        document.getElementById('userFeedback').innerHTML = 'Global update applied to all users!';
    } else {
        document.getElementById('userFeedback').innerHTML = 'Personal update applied to the user.';
    }
});
