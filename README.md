# stupid-site-generator

Hi, I created this script to glue different HTML files into one, "generating" a website. The way it works is very ~~stupid~~ simple.

The folder structure should be something like this:

- /public
- /public/css
- /public/js
- /src
- /src/includes

CSS, JS, images and other assets will not be touched by this script. It will only get HTML files from **/src** and **/src/includes**, merge them and copy to **/public**.

Execute it by running: **php build.php**

## Example

- /src/index.html
- /src/includes/head.html
- /src/includes/menu.html
- /src/includes/footer.html

### index.html

```html
<html>

<!-- var:title="Nice Page Title" -->
<!-- include:head.html -->

<body>
    <!-- var:indexCss="active" -->
    <!-- include:menu.html -->

    ...

    <!-- include:footer.html -->
</body>
</html>
```

### head.html

```html
<head>
    <title><!-- var:title --></title>
</head>
```

### menu.html

```html
<ul>
    <li class="{{ var:indexCss }}">Index</li>
    <li class="{{ var:aboutCss }}">About</li>
</ul>
```

### footer.html

```html
<footer>
    Somethig nice
</footer>
```

### Result index.html

```html
<html>

<head>
    <title>Nice Page Title</title>
</head>

<body>
<ul>
    <li class="active">Index</li>
    <li class="">About</li>
</ul>

    ...

<footer>
    Somethig nice
</footer>
</body>
</html>
```

No fancy shit. Told you it was stupid.
