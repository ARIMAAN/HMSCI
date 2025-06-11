class AttendanceTracker {
    constructor(config) {
        this.baseUrl = config.baseUrl;
        this.csrfName = config.csrfName;
        this.csrfHash = config.csrfHash;
        this.timers = {};
        this.totalMinutes = 0;
        this.startTime = null;
        this.phcLat = null;
        this.phcLng = null;
        this.init();
    }

    init() {
        // Check if attendance is active
        const isActive = localStorage.getItem('attendanceActive') === 'true';
        const startTime = localStorage.getItem('attendanceStartTime');
        
        if (isActive && startTime) {
            this.startTracking(true);
        }
    }

    startTracking(isRestore = false) {
        if (!isRestore) {
            localStorage.setItem('attendanceActive', 'true');
            localStorage.setItem('attendanceStartTime', Date.now().toString());
        }
        
        this.startTime = parseInt(localStorage.getItem('attendanceStartTime'));
        this.totalMinutes = Math.floor((Date.now() - this.startTime) / 60000);

        // Check location and update attendance every minute
        this.timers.location = setInterval(() => this.checkLocationAndUpdate(), 60000);
        this.timers.display = setInterval(() => this.updateDisplay(), 1000);

        // Update UI if on dashboard
        if (document.getElementById('startAttendance')) {
            document.getElementById('startAttendance').disabled = true;
            document.getElementById('stopAttendance').disabled = false;
        }
    }

    async stopTracking() {
        try {
            const data = {
                duration: this.totalMinutes,
                status: 'completed',
                [this.csrfName]: this.csrfHash
            };

            const response = await fetch(`${this.baseUrl}index.php?doctor/stop_attendance`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to stop attendance');
            }

            localStorage.removeItem('attendanceActive');
            localStorage.removeItem('attendanceStartTime');
            Object.values(this.timers).forEach(timer => clearInterval(timer));
            this.timers = {};
            this.totalMinutes = 0;
            this.startTime = null;

            return await response.json();
        } catch (error) {
            console.error('Stop attendance failed:', error);
            throw error;
        }
    }

    async checkLocationAndUpdate() {
        try {
            const position = await this.getCurrentPosition();
            const distance = this.calculateDistance(
                position.coords.latitude,
                position.coords.longitude,
                parseFloat(localStorage.getItem('phcLat')),
                parseFloat(localStorage.getItem('phcLng'))
            );

            if (distance <= 10000) { // 1km radius
                this.totalMinutes++;
                await this.updateAttendance();
            } else {
                alert("You are outside the PHC range. Please return to continue attendance tracking.");
            }
        } catch (error) {
            console.error('Location check failed:', error);
        }
    }

    async updateAttendance() {
        try {
            const formData = new FormData();
            formData.append(this.csrfName, this.csrfHash);
            formData.append('minutes', this.totalMinutes);
            formData.append('status', 'present');

            const response = await fetch(`${this.baseUrl}index.php?doctor/update_attendance`, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Failed to update attendance');
            }

            const data = await response.json();
            if (data.status === 'success') {
                localStorage.setItem('totalMinutes', this.totalMinutes.toString());
            }
        } catch (error) {
            console.error('Attendance update failed:', error);
        }
    }

    calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3; // Earth radius in meters
        const φ1 = lat1 * Math.PI / 180;
        const φ2 = lat2 * Math.PI / 180;
        const Δφ = (lat2 - lat1) * Math.PI / 180;
        const Δλ = (lon2 - lon1) * Math.PI / 180;

        const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                  Math.cos(φ1) * Math.cos(φ2) *
                  Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;
    }

    getCurrentPosition() {
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject);
        });
    }

    updateDisplay() {
        const startTime = parseInt(localStorage.getItem('attendanceStartTime'));
        if (!startTime || !document.getElementById('timePresent')) return;

        const diff = Math.floor((Date.now() - startTime) / 1000);
        const hours = Math.floor(diff / 3600);
        const minutes = Math.floor((diff % 3600) / 60);
        const seconds = diff % 60;
        
        document.getElementById('timePresent').textContent = 
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    setPHCCoordinates(lat, lng) {
        this.phcLat = lat;
        this.phcLng = lng;
        localStorage.setItem('phcLat', lat);
        localStorage.setItem('phcLng', lng);
    }
}
