//sweetalert
const btn_eliminar = document.getElementById('btn_eliminar');

btn_eliminar.addEventListener('click',function(){

  Swal.fire({
    title: "¿Estás seguro?",
    text: "¡No podrás revertir esto!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Sí, bórralo!"
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "¡Borrado!",
        text: "Se ha eliminado.",
        icon: "success"
      });
    }
  }); 
 

});



const guardarUsuario = document.getElementById('guardarUsuario');

guardarUsuario.addEventListener('click',function(){

  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    }
  });
  Toast.fire({
    icon: "success",
    title: "Usuario editado correctamente"
  });

});


const AgregarUsuario = document.getElementById('AgregarUsuario');

AgregarUsuario.addEventListener('click',function(){

  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    }
  });
  Toast.fire({
    icon: "success",
    title: "Usuario agregado correctamente"
  });

});



 