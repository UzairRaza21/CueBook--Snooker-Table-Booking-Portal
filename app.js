function openMenu(){
   document.getElementById("nav-col-links").classList.toggle("nav-col-links");
}

// To show current date in search bar
        // Get the current date
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');

        // Format the date as DD-MM-YYYY
        const formattedDate = `${day}-${month}-${year}`;

        // Set the value of the date input to the current date
        document.getElementById('search-date').value = formattedDate;