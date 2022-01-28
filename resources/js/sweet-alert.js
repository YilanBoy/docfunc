import Swal from "sweetalert2";

// variable "flashAlert" is defined in the "app.blade.php" file
Swal.fire({
    toast: true,
    position: "bottom-start",
    title: flashAlert.title,
    icon: flashAlert.icon,
    showConfirmButton: false,
    showCloseButton: true,
    timer: 6000,
    timerProgressBar: true,
});
