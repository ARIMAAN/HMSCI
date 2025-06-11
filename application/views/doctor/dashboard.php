<?php
    // Ensure system variables are defined
    $system_title = isset($system_title) ? $system_title : 'Hospital Management System';
    $system_name = isset($system_name) ? $system_name : 'HMSCI';
?>
<div class="container-fluid padded">

	<div class="row-fluid">

        <div class="span30">

            <!-- find me in partials/action_nav_normal -->

            <!--big normal buttons-->

            <div class="action-nav-normal">

                <div class="row-fluid">

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?doctor/manage_patient">

                        <i class="icon-user"></i>

                        <span><?php echo ('Patient');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?doctor/manage_appointment">

                        <i class="icon-exchange"></i>

                        <span><?php echo ('Appointment');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?doctor/manage_prescription">

                        <i class="icon-stethoscope"></i>

                        <span><?php echo ('Prescription');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?doctor/view_bed_status">

                        <i class="icon-hdd"></i>

                        <span><?php echo ('Bed Allotment');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?doctor/manage_report">

                        <i class="icon-hospital"></i>

                        <span><?php echo ('Manage Report');?></span>

                        </a>

                    </div>

                </div>

            </div>

        </div>

        <!---DASHBOARD MENU BAR ENDS HERE-->

    </div>

    <hr />

    <div class="row-fluid">
        <!-----ATTENDANCE LOGGING STARTS--->
        <div class="span12">
            <div class="box">
                <div class="box-header">
                    <div class="title">Log Attendance</div>
                </div>
                <div class="box-content">
                    <form id="attendanceForm" method="post" action="<?php echo base_url();?>index.php?doctor/log_attendance">
                        <div class="control-group">
                            <label class="control-label">Location (GPS Coordinates)</label>
                            <div class="controls">
                                <input type="text" id="location" name="location" placeholder="Fetching GPS Coordinates..." readonly required />
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" id="startAttendance" class="btn btn-success">Start Attendance</button>
                            <button type="button" id="stopAttendance" class="btn btn-danger" disabled>Stop Attendance</button>
                        </div>
                    </form>
                    <hr />
                    <div>
                        <p><strong>PHC Coordinates:</strong> <span id="phcCoordinates"></span></p>
                        <p><strong>Your Current Coordinates:</strong> <span id="currentCoordinates">Fetching...</span></p>
                        <p><strong>Time Present:</strong> <span id="timePresent">00:00:00</span></p>
                    </div>
                </div>
            </div>
        </div>
        <!-----ATTENDANCE LOGGING ENDS--->
    </div>

    <div class="row-fluid">
        <!-----CALENDAR SCHEDULE STARTS--->
        <div class="span6">
            <div class="box">
                <div class="box-header">
                    <div class="title">
                        <i class="icon-calendar"></i> <?php echo ('Calendar Schedule');?> 
                    </div>
                </div>
                <div class="box-content">
                    <div id="schedule_calendar" class="calendar"></div>
                </div>
            </div>
        </div>
        <!-----CALENDAR SCHEDULE ENDS--->

        <!-----NOTICEBOARD LIST STARTS--->
        <div class="span6">

            <div class="box">

                <div class="box-header">

                    <span class="title">

                        <i class="icon-reorder"></i> <?php echo ('Noticeboard');?> 

                    </span>

                </div>

                <div class="box-content scrollable" style="max-height: 500px; overflow-y: auto">

                	

                    <?php 

                    $notices	=	$this->db->get('noticeboard')->result_array();

                    foreach($notices as $row):

                    ?>

                    <div class="box-section news with-icons">

                        <div class="avatar blue">

                            <i class="icon-tag icon-2x"></i>

                        </div>

                        <div class="news-time">

                            <span><?php echo date('d',$row['create_timestamp']);?></span> <?php echo date('M',$row['create_timestamp']);?>

                        </div>

                        <div class="news-content">

                            <div class="news-title">

                                <?php echo $row['notice_title'];?> 

                            </div>

                            <div class="news-text">

                                 <?php echo $row['notice'];?> 

                            </div>

                        </div>

                    </div>

                    <?php endforeach;?>

                </div>

            </div>

        </div>
        <!-----NOTICEBOARD LIST ENDS--->
    </div>

</div>


<script src="<?php echo base_url();?>assets/js/attendance-tracker.js"></script>
<script>
// Utility functions - defined globally
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371e3; // Earth radius in meters
    const φ1 = lat1 * Math.PI / 180;
    const φ2 = lat2 * Math.PI / 180;
    const Δφ = (lat2 - lat1) * Math.PI / 180;
    const Δλ = (lon2 - lon1) * Math.PI / 180;

    const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
              Math.cos(φ1) * Math.cos(φ2) *
              Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c; // Distance in meters
}

$(document).ready(function() {
    // CSRF tokens
    const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    const csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

    // Variables
    let PHC_LAT, PHC_LNG;
    const PHC_RADIUS = 1000;
    let attendanceTimer = null;
    let totalMinutes = 0;
    let timerInterval = null;
    let startTime = null;
    let locationCheckTimer = null;
    const CHECK_INTERVAL = 5 * 60 * 1000;

    // Initialize single tracker instance
    const tracker = new AttendanceTracker({
        baseUrl: '<?php echo base_url();?>',
        csrfName: csrfName,
        csrfHash: csrfHash
    });

    // Calendar initialization
    $("#schedule_calendar").fullCalendar({
        header: {
            left: "prev,next",
            center: "title",
            right: "month,agendaWeek,agendaDay"
        },
        editable: 0,
        droppable: 0,
        events: [
            <?php 
            $notices = $this->db->get('noticeboard')->result_array();
            foreach($notices as $row):
            ?>
            {
                title: "<?php echo $row['notice_title'];?>",
                start: new Date(<?php echo date('Y',$row['create_timestamp']);?>, <?php echo date('m',$row['create_timestamp'])-1;?>, <?php echo date('d',$row['create_timestamp']);?>),
                end: new Date(<?php echo date('Y',$row['create_timestamp']);?>, <?php echo date('m',$row['create_timestamp'])-1;?>, <?php echo date('d',$row['create_timestamp']);?>)  
            },
            <?php
            endforeach;
            ?>
        ]
    });

    // Modify start attendance click handler
    $("#startAttendance").click(function() {
        if (!navigator.geolocation) {
            alert("Geolocation is not supported by this browser.");
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const currentLat = position.coords.latitude;
                const currentLng = position.coords.longitude;
                console.log("Current Coordinates:", currentLat, currentLng);

                const distance = calculateDistance(currentLat, currentLng, PHC_LAT, PHC_LNG);
                console.log("Distance to PHC:", distance);

                if (distance <= PHC_RADIUS) {
                    $("#location").val(currentLat + ", " + currentLng);
                    $("#startAttendance").prop("disabled", true);
                    $("#stopAttendance").prop("disabled", false);

                    attendanceTimer = setInterval(checkLocation, 60000); // Check every minute
                    startTime = new Date();
                    timerInterval = setInterval(updateTimer, 1000);
                    const data = {
                        location: $("#location").val()
                    };
                    data[csrfName] = csrfHash; // Add CSRF token
                    $.ajax({
                        url: "<?php echo base_url();?>index.php?doctor/log_attendance",
                        type: "POST",
                        data: data,
                        dataType: "json",
                        success: function(response) {
                            console.log("Start Attendance Response:", response);
                            if (response.status === "success") {
                                alert(response.message);
                            } else {
                                alert("Error: " + (response.message || "Unknown error occurred"));
                                $("#startAttendance").prop("disabled", false);
                                $("#stopAttendance").prop("disabled", true);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Start Attendance Failed:", textStatus, errorThrown);
                            console.error("Response Text:", jqXHR.responseText);
                            alert("Failed to log attendance. Please try again.");
                            $("#startAttendance").prop("disabled", false);
                            $("#stopAttendance").prop("disabled", true);
                        }
                    });
                } else {
                    alert("You are outside the PHC range. Please move closer to the PHC.");
                }
            },
            function(error) {
                console.error("Error fetching location:", error);
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert("User denied the request for Geolocation.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        alert("The request to get user location timed out.");
                        break;
                    case error.UNKNOWN_ERROR:
                        alert("An unknown error occurred.");
                        break;
                }
            }
        );
    });

    // Add stop attendance handler
    $("#stopAttendance").click(function() {
        $("#startAttendance, #stopAttendance").prop("disabled", true);
        
        const data = {
            location: $("#location").val(),
            duration: totalMinutes,
            status: 'completed',
            [csrfName]: csrfHash
        };

        $.ajax({
            url: "<?php echo base_url();?>index.php?doctor/stop_attendance",
            type: "POST",
            data: data,
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    // Clear all timers
                    clearInterval(attendanceTimer);
                    clearInterval(timerInterval);
                    clearInterval(locationCheckTimer);
                    
                    // Reset UI state
                    $("#timePresent").text("00:00:00");
                    $("#location").val("");
                    totalMinutes = 0;
                    startTime = null;
                    
                    // Reset localStorage
                    localStorage.removeItem('attendanceActive');
                    localStorage.removeItem('attendanceStartTime');
                    localStorage.removeItem('totalMinutes');
                    
                    // Reset buttons
                    $("#startAttendance").prop("disabled", false);
                    $("#stopAttendance").prop("disabled", true);
                    
                    alert("Attendance completed successfully");
                    
                    // Stop the tracker
                    tracker.stopTracking();
                } else {
                    $("#startAttendance").prop("disabled", true);
                    $("#stopAttendance").prop("disabled", false);
                    alert(response.message || "Failed to stop attendance");
                }
            },
            error: function(xhr, status, error) {
                console.error("Stop attendance failed:", error);
                console.error("Response:", xhr.responseText);
                $("#startAttendance").prop("disabled", true);
                $("#stopAttendance").prop("disabled", false);
                alert("Failed to stop attendance. Please try again.");
            }
        });
    });

    // Function to update the doctor's current coordinates
    function updateCurrentCoordinates() {
        if (!navigator.geolocation) {
            console.error("Geolocation is not supported by this browser.");
            $("#currentCoordinates").text("Geolocation not supported");
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const currentLat = position.coords.latitude;
                const currentLng = position.coords.longitude;
                $("#currentCoordinates").text(`${currentLat}, ${currentLng}`);
                console.log("Current Coordinates:", currentLat, currentLng);
            },
            function(error) {
                console.error("Error fetching location:", error);
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert("User denied the request for Geolocation.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        alert("The request to get user location timed out.");
                        break;
                    case error.UNKNOWN_ERROR:
                        alert("An unknown error occurred.");
                        break;
                }
            }
        );
    }

    // Function to check the doctor's current location
    function checkLocation() {
        if (!navigator.geolocation) {
            console.error("Geolocation is not supported by this browser.");
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const currentLat = position.coords.latitude;
                const currentLng = position.coords.longitude;
                const distance = calculateDistance(currentLat, currentLng, PHC_LAT, PHC_LNG);

                console.log("Checking location. Current Coordinates:", currentLat, currentLng);
                console.log("Distance to PHC:", distance);

                if (distance <= PHC_RADIUS) {
                    totalMinutes += 1; // Update every minute instead of 5
                    console.log("Inside PHC range. Total minutes:", totalMinutes);
                    const data = {
                        status: "present",
                        minutes: totalMinutes,
                        [csrfName]: csrfHash
                    };
                    
                    $.ajax({
                        url: "<?php echo base_url();?>index.php?doctor/update_attendance",
                        type: "POST",
                        data: data,
                        dataType: "json",
                        success: function(response) {
                            if (response.status === 'success') {
                                console.log('Attendance updated:', response.minutes, 'minutes');
                            } else {
                                console.error('Update failed:', response.message);
                                stopAttendance();
                                alert(response.message || 'Failed to update attendance');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX error:', error);
                            console.error('Response:', xhr.responseText);
                            stopAttendance();
                            alert('Failed to update attendance. Please try again.');
                        }
                    });
                } else {
                    alert("You are outside the PHC range. Please return to the PHC.");
                    clearInterval(attendanceTimer);
                    $("#stopAttendance").prop("disabled", true);
                    $("#startAttendance").prop("disabled", false);
                }
            },
            function(error) {
                console.error("Error fetching location:", error);
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert("User denied the request for Geolocation.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        alert("The request to get user location timed out.");
                        break;
                    case error.UNKNOWN_ERROR:
                        alert("An unknown error occurred.");
                        break;
                }
            }
        );
    }

    // Fetch PHC coordinates dynamically
    $.get("<?php echo base_url();?>index.php?doctor/get_phc_coordinates", function(data) {
        console.log("PHC Coordinates Response:", data); // Log the response
        if (data.latitude && data.longitude) {
            PHC_LAT = parseFloat(data.latitude);
            PHC_LNG = parseFloat(data.longitude);
            $("#phcCoordinates").text(`${PHC_LAT}, ${PHC_LNG}`);
            console.log("PHC Coordinates fetched:", PHC_LAT, PHC_LNG);
        } else {
            console.error("Error: PHC coordinates not found in response", data);
            $("#phcCoordinates").text("Error fetching PHC coordinates");
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("AJAX request failed:", textStatus, errorThrown);
        console.error("Response Text:", jqXHR.responseText); // Log the response text
        $("#phcCoordinates").text("Error fetching PHC coordinates");
    });

    function updateTimer() {
        if (!startTime) return;
        
        const now = new Date();
        const diff = Math.floor((now - startTime) / 1000); // difference in seconds
        
        const hours = Math.floor(diff / 3600);
        const minutes = Math.floor((diff % 3600) / 60);
        const seconds = diff % 60;
        
        $("#timePresent").text(
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
        );
    }

    // Update current coordinates every 5 seconds
    setInterval(updateCurrentCoordinates, 5000);

    // Check if attendance was already active
    $.get("<?php echo base_url();?>index.php?doctor/check_attendance_state", function(response) {
        if (response.status === 'success' && response.active) {
            // Restore attendance state
            startTime = new Date(response.start_time * 1000);
            $("#startAttendance").prop("disabled", true);
            $("#stopAttendance").prop("disabled", false);
            
            // Restart timers
            attendanceTimer = setInterval(checkLocation, 60000);
            timerInterval = setInterval(updateTimer, 1000);
            locationCheckTimer = setInterval(checkLocation, CHECK_INTERVAL);
            
            // Calculate initial duration
            const now = new Date();
            totalMinutes = Math.floor((now - startTime) / 60000);
        }
    });

    // Add beforeunload handler to persist attendance state
    $(window).on('beforeunload', function() {
        if (startTime !== null) {
            localStorage.setItem('attendanceActive', 'true');
            localStorage.setItem('attendanceStartTime', startTime.getTime());
        }
    });

    // Store PHC coordinates in localStorage when fetched
    $.get("<?php echo base_url();?>index.php?doctor/get_phc_coordinates", function(data) {
        if (data.latitude && data.longitude) {
            localStorage.setItem('phcLat', data.latitude);
            localStorage.setItem('phcLng', data.longitude);
            PHC_LAT = parseFloat(data.latitude);
            PHC_LNG = parseFloat(data.longitude);
            $("#phcCoordinates").text(`${PHC_LAT}, ${PHC_LNG}`);
        }
    });
});
</script>

<style>
.calendar {
    min-height: 400px;
    background: white;
    padding: 10px;
}
</style>