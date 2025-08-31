<?php
$conn = new mysqli("localhost", "root", "", "pg_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM residents WHERE status = 'active' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Residents</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
        background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
        font-family: 'Segoe UI', sans-serif;
        color: white;
        margin: 0;
        padding: 40px;
    }

    h1 {
        text-align: center;
        font-size: 36px;
        margin-bottom: 30px;
    }

    .search-box {
        text-align: center;
        margin-bottom: 30px;
    }

    .search-box input {
        padding: 10px;
        width: 300px;
        border-radius: 25px;
        border: none;
        font-size: 16px;
    }

    .residents-container {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .resident-card {
        display: flex;
        align-items: flex-start;
        background: rgba(255, 255, 255, 0.05);
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.4);
        transition: transform 0.2s;
    }

    .resident-card:hover {
        transform: scale(1.01);
    }

    .resident-photo {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #64ffda;
        box-shadow: 0 0 12px rgba(100, 255, 218, 0.8);
        margin-right: 20px;
        flex-shrink: 0;
    }

    .resident-info {
        flex-grow: 1;
    }

    .resident-info h2 {
        margin: 0;
        color: #64ffda;
    }

    .resident-info p {
        margin: 6px 0;
        font-size: 14px;
    }

    .aadhar-link {
        color: #00e6ff;
        text-decoration: underline;
        font-weight: bold;
    }

    .btn-group {
        margin-top: 15px;
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .btn {
        text-decoration:none;
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        font-size: 14px;
    }

    .btn-edit {
        background-color: #64ffda;
        color: #002b36;
    }

    .btn-edit:hover {
        background-color: #52e5c7;
    }

    .btn-delete {
        background-color: #ff4d4d;
        color: #fff;
    }

    .btn-delete:hover {
        background-color: #ff1a1a;
    }

    @media (max-width: 768px) {
        .resident-card {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .resident-photo {
            margin-bottom: 15px;
        }

        .resident-info {
            align-items: center;
        }

        .btn-group {
            justify-content: center;
        }
    }
    .details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin: 10px 0;
}

.details-grid p {
    margin: 6px 0;
    font-size: 16px;
}

@media (max-width: 768px) {
    .details-grid {
        grid-template-columns: 1fr; /* Stack on small screens */
    }
}

/* Schedule popup */
#schedulePopup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
}

.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  z-index: 999;
}

.popup-box {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #fff;
  color: #000;
  border-radius: 12px;
  padding: 25px;
  width: 400px;
  box-shadow: 0 10px 30px rgba(0, 255, 255, 0.3);
  z-index: 1000;
  /*animation: popupFade 0.3s ease-in-out;*/
}

@keyframes popupFade {
  from { transform: translateY(-30px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

.popup-box h3 {
  margin-top: 0;
  color: #091057;
  text-align: center;
}

.popup-box input,
.popup-box textarea {
  width: 100%;
  padding: 10px;
  margin: 8px 0;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 15px;
}

.popup-buttons {
  display: flex;
  justify-content: space-between;
  margin-top: 15px;
}

.btn-add {
  background-color: #64ffda;
  color: #003344;
  padding: 10px 20px;
  margin-top: 10px;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.btn-cancel {
  background-color: #ff4d4d;
  color: white;
  padding: 10px 20px;
  margin-top: 10px;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.btn-add:hover {
  background-color: #218838;
}

.btn-cancel:hover {
  background-color: #c82333;
}

  </style>
</head>
<body>

<h1>Active Residents</h1>

<div class="search-box">
  <input type="text" id="searchInput" placeholder="Search by name or room..." onkeyup="filterResidents()">
</div>

<div class="residents-container" id="residentsList">
  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="resident-card">
  <img class="resident-photo" src="get_image.php?id=<?= $row['id'] ?>" alt="Photo of <?= htmlspecialchars($row['name']) ?>">
  <div class="resident-info">
    <h2><?= htmlspecialchars($row['name']) ?></h2>

    <div class="details-grid">
      <div>
        <p><strong>Age:</strong> <?= $row['age'] ?></p>
        <p><strong>Gender:</strong> <?= $row['gender'] ?></p>
        <p><strong>Room:</strong> <?= htmlspecialchars($row['room_number']) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($row['contact']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>
      </div>
      <div>
        <p><strong>Monthly Rent:</strong> ₹<?= number_format($row['advance_amount'], 2) ?></p>
        <p><strong>Deposit:</strong> ₹<?= number_format($row['deposit_amount'], 2) ?></p>
        <p><strong>Fixed Amount:</strong> ₹<?= number_format($row['remaining_amount'], 2) ?></p>
        <p><strong>Aadhar:</strong> 
          <a class="aadhar-link" href="get_aadhar.php?id=<?= $row['id'] ?>" target="_blank">View Aadhar (PDF)</a>
        </p>
      </div>
    </div>

    <div class="btn-group">
      <a href="edit_resident.php?edit=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
      <button type="button" onclick="openSchedulePopup(<?= $row['id'] ?>)" class="btn btn-edit">Schedule</button>
      <a href="mark_left.php?id=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Mark this resident as Left?')">Mark Left</a>
      
    </div>
  </div>
</div>

    <?php endwhile; ?>
  <?php else: ?>
    <p style="text-align: center;">No active residents found.</p>
  <?php endif; ?>
</div>

<script>
function filterResidents() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const cards = document.querySelectorAll(".resident-card");
    cards.forEach(card => {
        const name = card.querySelector("h2").innerText.toLowerCase();
        const room = card.innerText.toLowerCase();
        card.style.display = (name.includes(input) || room.includes(input)) ? "flex" : "none";
    });
}

let currentResidentId = null;

function openSchedulePopup(residentId) {
  currentResidentId = residentId;
  document.getElementById('schedulePopup').style.display = 'block';
  document.getElementById('addFormPopup').style.display = 'none'; // Ensure form is closed
  loadScheduleList(residentId);
}

function closeSchedulePopup() {
  document.getElementById('schedulePopup').style.display = 'none';
}

function openScheduleForm() {
  document.getElementById('schedulePopup').style.display = 'none'; // Close viewer popup
  document.getElementById('addFormPopup').style.display = 'block'; // Open form in same position
  document.getElementById('scheduleResidentId').value = currentResidentId;
}

function closeScheduleForm() {
  document.getElementById('addFormPopup').style.display = 'none';
  document.getElementById('schedulePopup').style.display = 'block'; // Return to viewer
}

function loadScheduleList(residentId) {
  fetch(`schedule.php?resident_id=${residentId}`)
    .then(res => res.json())
    .then(data => {
      const list = document.getElementById("scheduleList");
      list.innerHTML = data.length
        ? data.map(d =>
            `<div style="margin-bottom: 12px; padding: 12px; background:#f1f1f1; color:#000; border-radius: 8px;">
                <strong>Start:</strong> ${d.start_date}<br>
                <strong>End:</strong> ${d.end_date}<br>
                <strong>Note:</strong> ${d.note}
             </div>`
          ).join("")
        : "<p style='color:#000;'>No schedules found.</p>";
    });
}

document.getElementById('scheduleForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch('schedule.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      closeScheduleForm(); // Return to viewer
      loadScheduleList(currentResidentId);
    } else {
      alert("Error saving schedule.");
    }
  });
});

function deleteSchedule(id) {
    if (confirm("Are you sure you want to delete this schedule?")) {
        fetch('schedule.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: 'delete',
                id: id
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') loadSchedules(currentResidentId);
        });
    }
}

function openEditForm(id) {
    // Optional: fetch data by ID via JS or reuse existing data
    // Open a popup with form: start_date, end_date, note, and "Save Changes" button
    // On submit: send POST to schedule.php with action=edit
}

let currentEditData = {};

function openEditPopup(id, start, end, note) {
  // Close any existing viewer popup if needed
  document.getElementById("editSchedulePopup").style.display = "flex";
  document.getElementById("edit-id").value = id;
  document.getElementById("edit-start").value = start;
  document.getElementById("edit-end").value = end;
  document.getElementById("edit-note").value = note;

  currentEditData = { id, start, end, note };
}

function closeEditPopup() {
  document.getElementById("editSchedulePopup").style.display = "none";
  document.getElementById("editScheduleForm").reset();
}

document.getElementById("editScheduleForm").addEventListener("submit", function(e) {
  e.preventDefault();

  const formData = new FormData(this);
  formData.append("action", "edit");

  fetch("schedule.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === "success") {
      alert("Schedule updated successfully.");
      closeEditPopup();
      loadSchedules(currentResidentId);  // refresh list
    } else {
      alert("Update failed: " + data.message);
    }
  })
  .catch(err => {
    console.error(err);
    alert("Something went wrong.");
  });
});


</script>

</body>
</html>
<!-- Schedule Viewer Popup -->
<div id="schedulePopup" style="display:none;">
  <div class="popup-overlay" onclick="closeSchedulePopup()"></div>
  <div class="popup-box">
    <h3>Schedules</h3>
    <button class="btn-add" onclick="openScheduleForm()">➕ Add New Schedule</button>
    <div id="scheduleList" style="margin-top: 20px;"></div>
  </div>
</div>

<!-- Schedule Entry Form Popup -->
<div id="addFormPopup" style="display:none;">
  <div class="popup-overlay" onclick="closeScheduleForm()"></div>
  <div class="popup-box">
    <h3>Add Schedule</h3>
    <form id="scheduleForm" action="schedule.php" method="POST">
      <input type="hidden" name="resident_id" id="scheduleResidentId">
      <label>Start Date</label>
      <input type="date" name="start_date" required>
      <label>End Date</label>
      <input type="date" name="end_date" required>
      <label>Note</label>
      <textarea name="note" rows="3" required></textarea>
      <div class="popup-buttons">
        <button type="submit" class="btn-add">Add</button>
        <button type="button" onclick="closeScheduleForm()" class="btn-cancel">Cancel</button>
      </div>
    </form>
  </div>
</div>
<!-- Edit Schedule Popup -->
<div id="editSchedulePopup" class="popup-overlay" style="display:none;">
  <div class="popup-box">
    <h3>Edit Schedule</h3>
    <form id="editScheduleForm">
      <input type="hidden" name="id" id="edit-id">

      <label>Start Date</label>
      <input type="date" name="start_date" id="edit-start" required>

      <label>End Date</label>
      <input type="date" name="end_date" id="edit-end" required>

      <label>Note</label>
      <textarea name="note" id="edit-note" rows="3" required></textarea>

      <div class="btn-group">
        <button type="submit" class="btn-save">Save Changes</button>
        <button type="button" onclick="closeEditPopup()" class="btn-cancel">Cancel</button>
      </div>
    </form>
  </div>
</div>
