<html>

<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.6.16/dist/css/uikit.min.css" />
</head>

<body>

  <div id="app" class="uk-container">

    <h1 class="uk-heading-medium">Welcome</h1>

    <form class="uk-form-stacked">

      <div class="uk-margin">
        <label class="uk-form-label" for="form-stacked-text">Code:</label>
        <div class="uk-form-controls">
          <input class="uk-input" type="text" v-model="serial" placeholder="123-456-789">
        </div>
      </div>

      <button class="uk-button uk-button-default" @click.prevent="check">
        <span uk-icon="icon: search"></span>
        Check</button>

    </form>


    <h3 class="uk-heading-small" v-if="loading === true">⌛️</h3>

    <h3 class="uk-heading-small" v-if="loading === false && results.length === 0 && searched === true">No results found!</h3>

    <table class="uk-table uk-table-striped" v-if="loading === false && results.length > 0 && searched === true">
      <thead>
        <tr>
          <th>Serial</th>
          <th>Name</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="result in results">
          <td>{{result.uid}}</td>
          <td>{{result.data.title[0].text}}</td>
        </tr>
      </tbody>
    </table>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.16/dist/js/uikit.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.16/dist/js/uikit-icons.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

  <script>
    var app = new Vue({
      el: '#app',
      data: {
        ref: 0,
        serial: '',
        results: [],
        searched: false,
        loading: false,
      },
      mounted: function() {
        fetch('https://bottles.prismic.io/api/v2')
          .then(response => response.json())
          .then(data => {
            this.ref = data?.refs?. [0]?.ref;
          });
      },
      methods: {
        check: function() {
          this.searched = true;
          if (this.serial === '') {
            this.results = [];
            return;
          }
          this.loading = true;
          fetch(`https://bottles.cdn.prismic.io/api/v2/documents/search?ref=${this.ref}&q=[[fulltext(my.bottle.uid, "${this.serial}")]]`)
            .then(response => response.json())
            .then(data => {
              this.results = data?.results || [];
              this.loading = false;
            });
        }
      }
    });
  </script>

</body>

</html>
