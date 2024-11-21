<?php
include 'php/dbConnect.php';
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Announcement</title>
  <link rel="stylesheet" href="css/admin_announcement.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lucida Consol&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
  <div class="container">


    <?php
      include 'admin_nav.php';
    ?>

<div class="content">
            <div class="announcement-container row">
                <!-- Add Announcement Panel -->
                <div class="col-sm-12 col-md-6">
                    <div class="admin-panel card p-4">
                        <h3 class="card-title mb-4">Add Announcement</h3>
                        <form id="announcementForm" method="post" action="php/functions.php?op=add_announcement" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" id="title" name="title" class="form-control" placeholder="Enter title" required />
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" id="image" name="image" class="form-control" accept=".jpg, .jpeg, .png" />
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea id="content" name="content" class="form-control" placeholder="Enter announcement details" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>

                <!-- Announcement List Panel -->
                <div class="col-sm-12 col-md-6">
                    <div class="announcement-list card p-4">
                        <h1 class="card-title mb-4">All Announcements</h1>
                        <div class="list-header d-flex justify-content-between align-items-center mb-3">
                            <span class="h5">Announcements</span>
                            <select id="sortDropdown" class="form-select" onchange="sortAnnouncements()">
                                <option value="desc">Latest to Oldest</option>
                                <option value="asc">Oldest to Latest</option>
                            </select>
                        </div>

                        <ul id="announcementDisplay" class="list-group"></ul>
                        <button id="showMoreBtn" onclick="showMore()" class="btn btn-secondary mt-3">Show More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Viewing Announcement -->
    <div id="announcementModal" class="modal fade" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalDate" class="text-muted"></p>
                    <img id="modalImage" src="" alt="Announcement Image" class="img-fluid mb-3" style="display:none;" />
                    <p id="modalContent"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Required for Modal and other interactions) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>



  <script>
    let announcements = [];
    async function fetchAnnouncements() {
      try {
        const response = await fetch('php/functions.php?op=admin_all_announcement'); // Adjust the path as needed
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const announcements = await response.json();
        return announcements;
      } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
      }
    }

    // Call the function and assign the result to the announcements variable
    fetchAnnouncements().then(fetchedAnnouncements => {
      if (fetchedAnnouncements) {
        announcements = fetchedAnnouncements; // Update the global announcements array
        sortAnnouncements(); // Sort and display the announcements after fetching them
        displayAnnouncements(); // Display the fetched announcements
      }
    });


    let itemsToShow = 5;

    function displayAnnouncements() {
      const announcementDisplay = document.getElementById("announcementDisplay");
      announcementDisplay.innerHTML = '';

      // Define the maximum length of content to display before truncation
      const maxLength = 220;

      const visibleAnnouncements = announcements.slice(0, itemsToShow);

      visibleAnnouncements.forEach((announcement, index) => {
        const li = document.createElement("li");

        // Check if the content exceeds the maxLength
        let truncatedContent = announcement.content;
        if (announcement.content.length > maxLength) {
          truncatedContent = announcement.content.substring(0, maxLength) + '......';
        }

        // Render the truncated content in the list item
        li.innerHTML = `<strong>${announcement.title}</strong><br/>${truncatedContent}<br/><em>${announcement.date}</em>`;
        li.style.cursor = 'pointer'; // Make the list item clickable
        li.onclick = () => openModal(index); // Add click event to show the modal

        announcementDisplay.appendChild(li);
      });

      // Check if there are more announcements to show
      if (announcements.length > itemsToShow) {
        document.getElementById("showMoreBtn").style.display = "block";
      } else {
        document.getElementById("showMoreBtn").style.display = "none";
      }
    }

    function openModal(index) {
      const selectedAnnouncement = announcements[index];

      const formattedContent = selectedAnnouncement.content
        .replace(/\n/g, "<br>")
        .replace(/ {2}/g, "&nbsp;&nbsp;");

      // Update the modal title, content, and date
      document.getElementById("modalTitle").textContent = selectedAnnouncement.title;
    document.getElementById("modalContent").innerHTML = formattedContent;
      document.getElementById("modalDate").textContent = `Date: ${selectedAnnouncement.date}`;

      // Check if the image exists and update the modal image
      const modalImage = document.getElementById("modalImage");
      if (selectedAnnouncement.image) {
          modalImage.src = selectedAnnouncement.image;
          modalImage.style.display = "block";
      } else {
          modalImage.style.display = "none";
      }

      // Open the modal using Bootstrap's JS API
      const modal = new bootstrap.Modal(document.getElementById('announcementModal'));
      modal.show();
    }

    function closeModal() {
      document.getElementById("announcementModal").style.display = "none"; // Hide the modal
    }

    window.onclick = function(event) {
      const modal = document.getElementById("announcementModal");
      if (event.target === modal) {
        modal.style.display = "none"; // Close the modal if the user clicks outside of it
      }
    }

    function showMore() {
      itemsToShow += 5;
      displayAnnouncements();
    }

    function sortAnnouncements() {
      const sortOrder = document.getElementById("sortDropdown").value;

      announcements.sort((a, b) => {
        const dateA = new Date(a.date);
        const dateB = new Date(b.date);

        if (sortOrder === 'asc') {
          return dateA - dateB; // Sort by oldest first
        } else {
          return dateB - dateA; // Sort by newest first
        }
      });

      displayAnnouncements(); // Re-render the list
    }

    // Initialize announcements by sorting and displaying the first set
    sortAnnouncements();
    displayAnnouncements();
  </script>
</body>

</html>