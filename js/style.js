// sweet alert js code follows
window.showAlert = async (icon, title) => {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-right",
    iconColor: "white",
    customClass: {
      popup: "colored-toast",
    },
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
  });
  await Toast.fire({
    icon: icon,
    title: title,
  });

  // await Toast.fire({
  //   icon: "success",
  //   title: "Success",
  // });
  // await Toast.fire({
  //   icon: "error",
  //   title: "Error",
  // });
  // await Toast.fire({
  //   icon: "warning",
  //   title: "Warning",
  // });
};
//sweet alert ends
