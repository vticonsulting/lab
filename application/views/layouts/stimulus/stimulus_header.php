<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <script src="https://unpkg.com/stimulus/dist/stimulus.umd.js"></script>
  <script>
    (() => {
      const application = Stimulus.Application.start()

      application.register("hello", class extends Stimulus.Controller {
        static get targets() {
          return [ "name" ]
        }

        // …
      })
    })()
  </script>
</head>
<body>
