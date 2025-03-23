
console.log("custom js loaded.");

function submitLogout () {
    console.log("submitLogout IN");
   const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('_token', csrfToken);

    fetch('/logout', {
        method:'POST',
        body:formData
    })
    .then(data => { 
        console.log("success");
        location.reload();
    })
    .catch(error => console.error("Error:", error));
    
}

export default submitLogout;