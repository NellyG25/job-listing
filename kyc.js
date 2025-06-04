
document.getElementById('kyc-form').addEventListener('submit', function(e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

  fetch('kyc_email.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    const modal = new bootstrap.Modal(document.getElementById('kycResponseModal'));
    const messageEl = document.getElementById('kycModalMessage');

    if (data.status === 'success') {
      messageEl.innerHTML = '<h5 class="text-success">🎉 KYC submitted successfully!</h5>';
      form.reset();
    } else if (data.status === 'nofile') {
      messageEl.innerHTML = '<h5 class="text-warning">⚠️ Please upload all required documents.</h5>';
    } else {
      messageEl.innerHTML = '<h5 class="text-danger">❌ An error occurred. Please try again.</h5>';
    }

    modal.show();
  })
  .catch(error => {
    console.error(error);
    const modal = new bootstrap.Modal(document.getElementById('kycResponseModal'));
    document.getElementById('kycModalMessage').innerHTML = '<h5 class="text-danger">❌ Network error. Please try again.</h5>';
    modal.show();
  });
});

