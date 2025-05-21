// assets/js/script.js
document.addEventListener('DOMContentLoaded', function() {
    const registrationForm = document.getElementById('registrationForm');
    
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            // Only check if passwords match (optional)
            if (password.value !== confirmPassword.value) {
                alert('Passwords do not match!');
                e.preventDefault();
                confirmPassword.focus();
            }
        });
    }

    // Keep course loading functionality
    const departmentSelect = document.getElementById('departmentSelect');
    if (departmentSelect) {
        departmentSelect.addEventListener('change', function() {
            const deptId = this.value;
            const courseSelect = document.getElementById('courseSelect');
            
            if (!deptId) {
                courseSelect.innerHTML = '<option value="">Select Department First</option>';
                courseSelect.disabled = true;
                return;
            }

            fetch(`../api/get_courses.php?department_id=${deptId}`)
                .then(response => response.json())
                .then(data => {
                    courseSelect.innerHTML = data.map(course => 
                        `<option value="${course.id}">${course.course_name}</option>`
                    ).join('');
                    courseSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error loading courses:', error);
                    courseSelect.innerHTML = '<option value="">Error loading courses</option>';
                });
        });
    }
});