
document.getElementById('application-form').addEventListener('submit', function(e) {
  e.preventDefault(); // prevent default form submit

  const form = e.target;
  const formData = new FormData(form);

  fetch('send_email_job.php', {  // change to your PHP processor filename
    method: 'POST',
    body: formData
  })
  .then(response => response.json())  // expect JSON from PHP
  .then(data => {
    const modalEl = document.getElementById('responseModal');
    const modalMessage = document.getElementById('modalMessage');
    const modal = new bootstrap.Modal(modalEl);

    if(data.status === 'success'){
      modalMessage.innerHTML = '<h5 class="text-success">🎉 Application submitted successfully!</h5>';
      form.reset();
    } else if(data.status === 'nofile'){
      modalMessage.innerHTML = '<h5 class="text-warning">⚠️ Please upload your CV.</h5>';
    } else {
      modalMessage.innerHTML = '<h5 class="text-danger">❌ Something went wrong. Please try again.</h5>';
    }

    modal.show();
  })
  .catch(error => {
    console.error('Error:', error);
    const modal = new bootstrap.Modal(document.getElementById('responseModal'));
    document.getElementById('modalMessage').innerHTML = '<h5 class="text-danger">❌ Network error. Please try again.</h5>';
    modal.show();
  });
});
