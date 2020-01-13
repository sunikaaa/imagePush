let app = new Vue({
  el: '#app',
  data: {
    formData: ''
  },
  methods: {
    selectFile(e) {
      //   e.preventDefault()
      this.formData = e.target.files[0];
      console.log(this.formData);
    },
    upload() {
      let formData = new FormData();
      formData.append('yourFileKey', this.formData);
      let config = {
        headers: {
          'content-type': 'multipart/form-data'
        }
      };
      console.log(this.formData);
      axios
        .post('uploadFile', this.formData, config)
        .catch(error => console.log(error));
    }
  }
});
