$("#edit_coach").validate({
  rules: {
    firstName: {
      required: true,
      maxlength: 45,
    },
    lastName: {
      required: true,
    },
    email: {
      required: true,
      maxlength: 100,
      remote: {
        url: admin_url + "firm/unique_email",
        type: "get",
        data: {
          id: function () {
            return $("input[name='id']").val();
          },
          email: function () {
            return $("input[name='email']").val();
          },
        },
      }
    },
    profileImage: {
      // required: true,
      accept: "jpeg, image/jpg, image/png,image/JPEG, image/JPG, image/PNG",
    },
    // password: {
    //   normalizer: function (value) {
    //     return $.trim(value);
    //   },
    //   required: true,
    //   minlength: 8,
    //   regex_password: true,
    // },
    contact: {
      // equalTo: "#vPassword",
      required: true,
    },
    tAddress: {
      required: true,
      maxlength: 250,
    },
    vMobileNo: {
      required: true,
      mobilenumberlength: true,
    },
  },
  messages: {
    email: {
      remote: "this username already exist",
    }
  }
});


// code to validate admin profile form with jquery validation
$("#edit_profile").validate({
  rules: {
    firstName: {
      required: true,
      maxlength: 45,
    },
    lastName: {
      required: true,
    },
    email: {
      required: true,
      email: true,
      remote: admin_url + "profile/unique_email",
      maxlength: 100,
    },
    profileImage: {
      // required: true,
      accept: "jpeg, image/jpg, image/png,image/JPEG, image/JPG, image/PNG",
    },
    contact: {
      // equalTo: "#vPassword",
      required: true,
    },
    tAddress: {
      required: true,
      maxlength: 250,
    },
    vMobileNo: {
      required: true,
      mobilenumberlength: true,
    },
  },
  messages: {
    email: {
      remote: "this username already exist",
    }
  }
});



$("#add_job").validate({
  rules: {
    job_name: {
      required: true,
    },
    firmId: {
      required: true,
    },
    total_quantity: {
      required: true,
    },
    qtyTpe: {
      required: true,
    },
    qtyPrice: {
      required: true,
    },
    commodityId: {
      required: true,
    },
    deliveryType:{
      required: true
    },
    dealvalid:{
      required:true
    }
  },
  messages: {
    email: {
      remote: "this username already exist",
    }
  }
});

$("#submitCommodity").validate({
  rules: {
    commodity: {
      required: true,
    },
  },
  messages: {
    email: {
      remote: "this username already exist",
    }
  }
});

$("#edit_firm").validate({
  rules: {
    firm_name: {
      required: true,
    },
    address: {
      required: true,
    },
  },
});