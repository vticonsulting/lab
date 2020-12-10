---
title: Elements
elements:
  - nav:
      'The HTML <nav> element represents a section of a page whose purpose is to provide navigation
      links, either within the current document or to other documents. Common examples of navigation
      sections are menus, tables of contents, and indexes.'
  - header:
      The HTML <header> element represents introductory content, typically a group of introductory
      or navigational aids. It may contain some heading elements but also a logo, a search form, an
      author name, and other elements.
  - address:
      The HTML <address> element indicates that the enclosed HTML provides contact information for a
      person or people, or for an organization.
  - footer:
      The HTML <footer> element represents a footer for its nearest sectioning content or sectioning
      root element. A footer typically contains information about the author of the section,
      copyright data or links to related documents.
  - aside:
      The HTML <aside> element represents a portion of a document whose content is only indirectly
      related to the document's main content. Asides are frequently presented as sidebars or
      call-out boxes.
  - main:
      The HTML <main> element represents the dominant content of the <body> of a document. The main
      content area consists of content that is directly related to or expands upon the central topic
      of a document, or the central functionality of an application.
  - section:
      'The HTML <section> element represents a standalone section — which doesn’t have a more
      specific semantic element to represent it — contained within an HTML document. Typically, but
      not always, sections have a heading.'
  - article:
      'The HTML <article> element represents a self-contained composition in a document, page,
      application, or site, which is intended to be independently distributable or reusable (e.g.,
      in syndication). Examples include: a forum post, a magazine or newspaper article, or a blog
      entry, a product card, a user-submitted comment, an interactive widget or gadget, or any other
      independent item of content.'
  - time:
      The HTML <time> element represents a specific period in time. It may include the datetime
      attribute to translate dates into machine-readable format, allowing for better search engine
      results or custom features such as reminders.
  - summary:
      The HTML Disclosure Summary element (<summary>) element specifies a summary, caption, or
      legend for a <details> element's disclosure box. Clicking the <summary> element toggles the
      state of the parent <details> element open and closed.
  - small:
      The HTML <small> element represents side-comments and small print, like copyright and legal
      text, independent of its styled presentation. By default, it renders text within it one
      font-size smaller, such as from small to x-small.
  - q:
      The HTML <q> element indicates that the enclosed text is a short inline quotation. Most modern
      browsers implement this by surrounding the text in quotation marks. This element is intended
      for short quotations that don't require paragraph breaks; for long quotations use the
      <blockquote> element.
  - progrss:
      The HTML <progress> element displays an indicator showing the completion progress of a task,
      typically displayed as a progress bar.
  - meter:
      The HTML <meter> element represents either a scalar value within a known range or a fractional
      value.
  - mark:
      The HTML Mark Text element (<mark>) represents text which is marked or highlighted for
      reference or notation purposes, due to the marked passage's relevance or importance in the
      enclosing context.
  - figure:
      The HTML <figure> (Figure With Optional Caption) element represents self-contained content,
      potentially with an optional caption, which is specified using the (<figcaption>) element. The
      figure, its caption, and its contents are referenced as a single unit.
  - figcaption:
      The HTML <figcaption> or Figure Caption element represents a caption or legend describing the
      rest of the contents of its parent <figure> element.
  - legend: The HTML <legend> element represents a caption for the content of its parent <fieldset>.
  - kbd:
      The HTML Keyboard Input element (<kbd>) represents a span of inline text denoting textual user
      input from a keyboard, voice input, or any other text entry device. By convention, the user
      agent defaults to rendering the contents of a <kbd> element using its default monospace font,
      although this is not mandated by the HTML standard.
  - dl:
      The HTML <dl> element represents a description list. The element encloses a list of groups of
      terms (specified using the <dt> element) and descriptions (provided by <dd> elements). Common
      uses for this element are to implement a glossary or to display metadata (a list of key-value
      pairs).
  - dt:
      The HTML <dt> element specifies a term in a description or definition list, and as such must
      be used inside a <dl> element. It is usually followed by a <dd> element; however, multiple
      <dt> elements in a row indicate several terms that are all defined by the immediate next <dd>
      element. The subsequent <dd> (Description Details) element provides the definition or other
      related text associated with the term specified using <dt>.
  - caption: The HTML <caption> element specifies the caption (or title) of a table.
  - cite:
      The HTML Citation element (<cite>) is used to describe a reference to a cited creative work,
      and must include the title of that work. The reference may be in an abbreviated form according
      to context-appropriate conventions related to citation metadata.
  - code:
      The HTML <code> element displays its contents styled in a fashion intended to indicate that
      the text is a short fragment of computer code. By default, the content text is displayed using
      the user agent's default monospace font.
  - col:
      The HTML <col> element defines a column within a table and is used for defining common
      semantics on all common cells. It is generally found within a <colgroup> element.
  - data:
      The HTML <data> element links a given piece of content with a machine-readable translation. If
      the content is time- or date-related, the <time> element must be used.
  - datalist:
      The HTML <datalist> element contains a set of <option> elements that represent the permissible
      or recommended options available to choose from within other controls.
  - dd:
      The HTML <dd> element provides the description, definition, or value for the preceding term
      (<dt>) in a description list (<dl>).
  - details:
      The HTML Details Element (<details>) creates a disclosure widget in which information is
      visible only when the widget is toggled into an "open" state. A summary or label can be
      provided using the <summary> element. A disclosure widget is typically presented onscreen
      using a small triangle which rotates (or twists) to indicate open/closed status, with a label
      next to the triangle. If the first child of the <details> element is a <summary>, the contents
      of the <summary> element are used as the label for the disclosure widget.
  - dialog:
      The HTML <dialog> element represents a dialog box or other interactive component, such as a
      dismissable alert, inspector, or subwindow. https://github.com/GoogleChrome/dialog-polyfill
---

```html
<article class="forecast">
  <h1>Weather forecast for Seattle</h1>
  <article class="day-forecast">
    <h2>03 March 2018</h2>
    <p>Rain.</p>
  </article>
  <article class="day-forecast">
    <h2>04 March 2018</h2>
    <p>Periods of rain.</p>
  </article>
  <article class="day-forecast">
    <h2>05 March 2018</h2>
    <p>Heavy rain.</p>
  </article>
</article>
```

<p>The Cure will be celebrating their 40th anniversary on <time datetime="2018-07-07">July 7</time> in London's Hyde Park.</p>

<p>The concert starts at <time datetime="20:00">20:00</time> and you'll be able to enjoy the band for at least <time datetime="PT2H30M">2h 30m</time>.</p>

<details>
    <summary>I have keys but no doors. I have space but no room. You can enter but can’t leave. What am I?</summary>
    A keyboard.
</details>

<p>When Dave asks HAL to open the pod bay door, HAL answers: <q cite="https://www.imdb.com/title/tt0062622/quotes/qt0396921">I'm sorry, Dave. I'm afraid I can't do that.</q></p>

<fieldset>
    <legend>Choose your favorite monster</legend>

    <input type="radio" id="kraken" name="monster">
    <label for="kraken">Kraken</label><br/>

    <input type="radio" id="sasquatch" name="monster">
    <label for="sasquatch">Sasquatch</label><br/>

    <input type="radio" id="mothman" name="monster">
    <label for="mothman">Mothman</label>

</fieldset>

<figure>
    <img src="/media/cc0-images/elephant-660-480.jpg"
         alt="Elephant at sunset">
    <figcaption>An elephant at sunset</figcaption>
</figure>

```css
figure {
  border: thin #c0c0c0 solid;
  display: flex;
  flex-flow: column;
  padding: 5px;
  max-width: 220px;
  margin: auto;
}

img {
  max-width: 220px;
  max-height: 150px;
}

figcaption {
  background-color: #222;
  color: #fff;
  font: italic smaller sans-serif;
  padding: 3px;
  text-align: center;
}
```

<p>Cryptids of Cornwall:</p>

<dl>
    <dt>Beast of Bodmin</dt>
    <dd>A large feline inhabiting Bodmin Moor.</dd>

    <dt>Morgawr</dt>
    <dd>A sea serpent.</dd>

    <dt>Owlman</dt>
    <dd>A giant owl-like creature.</dd>

</dl>

<figure>
    <blockquote cite="https://www.huxley.net/bnw/four.html">
        <p>Words can be like X-rays, if you use them properly—they’ll go through anything. You read and you’re pierced.</p>
    </blockquote>
    <figcaption>—Aldous Huxley, <cite>Brave New World</cite></figcaption>
</figure>

```css
blockquote {
  margin: 0;
}

blockquote p {
  padding: 15px;
  background: #eee;
  border-radius: 5px;
}

blockquote p::before {
  content: '\201C';
}

blockquote p::after {
  content: '\201D';
}
```

<table>
    <caption>He-Man and Skeletor facts</caption>
    <tr>
        <td> </td>
        <th scope="col" class="heman">He-Man</th>
        <th scope="col" class="skeletor">Skeletor</th>
    </tr>
    <tr>
        <th scope="row">Role</th>
        <td>Hero</td>
        <td>Villain</td>
    </tr>
    <tr>
        <th scope="row">Weapon</th>
        <td>Power Sword</td>
        <td>Havoc Staff</td>
    </tr>
    <tr>
        <th scope="row">Dark secret</th>
        <td>Expert florist</td>
        <td>Cries at romcoms</td>
    </tr>
</table>

```css
caption {
  padding: 10px;
  caption-side: bottom;
}

table {
  border-collapse: collapse;
  border: 2px solid rgb(200, 200, 200);
  letter-spacing: 1px;
  font-family: sans-serif;
  font-size: 0.8rem;
}

td,
th {
  border: 1px solid rgb(190, 190, 190);
  padding: 7px 5px;
}

th {
  background-color: rgb(235, 235, 235);
}

td {
  text-align: center;
}

tr:nth-child(even) td {
  background-color: rgb(250, 250, 250);
}

tr:nth-child(odd) td {
  background-color: rgb(240, 240, 240);
}

.heman {
  font: 1.4rem molot;
  text-shadow: 1px 1px 1px #fff, 2px 2px 1px #000;
}

.skeletor {
  font: 1.7rem rapscallion;
  letter-spacing: 3px;
  text-shadow: 1px 1px 0 #fff, 0 0 9px #000;
}
```

<blockquote>
    <p>It was a bright cold day in April, and the clocks were striking thirteen.</p>
    <footer>
        First sentence in <cite><a href="http://www.george-orwell.org/1984/0.html">Nineteen Eighty-Four</a></cite> by George Orwell (Part 1, Chapter 1).
    </footer>
</blockquote>

<p>The <code>push()</code> method adds one or more elements to the end of an array and returns the new length of the array.</p>

```css
code {
  background-color: #eee;
  border-radius: 3px;
  font-family: courier, monospace;
  padding: 0 3px;
}
```

<table>
    <caption>Superheros and sidekicks</caption>
    <colgroup>
        <col>
        <col span="2" class="batman">
        <col span="2" class="flash">
    </colgroup>
    <tr>
        <td> </td>
        <th scope="col">Batman</th>
        <th scope="col">Robin</th>
        <th scope="col">The Flash</th>
        <th scope="col">Kid Flash</th>
    </tr>
    <tr>
        <th scope="row">Skill</th>
        <td>Smarts</td>
        <td>Dex, acrobat</td>
        <td>Super speed</td>
        <td>Super speed</td>
    </tr>
</table>

<table>
    <caption>Superheros and sidekicks</caption>
    <colgroup>
        <col>
        <col span="2" class="batman">
        <col span="2" class="flash">
    </colgroup>
    <tr>
        <td> </td>
        <th scope="col">Batman</th>
        <th scope="col">Robin</th>
        <th scope="col">The Flash</th>
        <th scope="col">Kid Flash</th>
    </tr>
    <tr>
        <th scope="row">Skill</th>
        <td>Smarts</td>
        <td>Dex, acrobat</td>
        <td>Super speed</td>
        <td>Super speed</td>
    </tr>
</table>

<table>
    <caption>Superheros and sidekicks</caption>
    <colgroup>
        <col>
        <col span="2" class="batman">
        <col span="2" class="flash">
    </colgroup>
    <tr>
        <td> </td>
        <th scope="col">Batman</th>
        <th scope="col">Robin</th>
        <th scope="col">The Flash</th>
        <th scope="col">Kid Flash</th>
    </tr>
    <tr>
        <th scope="row">Skill</th>
        <td>Smarts</td>
        <td>Dex, acrobat</td>
        <td>Super speed</td>
        <td>Super speed</td>
    </tr>
</table>

```css
.batman {
  background-color: #d7d9f2;
}

.flash {
  background-color: #ffe8d4;
}

caption {
  padding: 8px;
  caption-side: bottom;
}

table {
  border-collapse: collapse;
  border: 2px solid rgb(100, 100, 100);
  letter-spacing: 1px;
  font-family: sans-serif;
  font-size: 0.7rem;
}

td,
th {
  border: 1px solid rgb(100, 100, 100);
  padding: 10px 10px;
}

td {
  text-align: center;
}
```

<p>New Products:</p>
<ul>
    <li><data value="398">Mini Ketchup</data></li>
    <li><data value="399">Jumbo Ketchup</data></li>
    <li><data value="400">Mega Jumbo Ketchup</data></li>
</ul>

<label for="myBrowser">Choose a browser from this list:</label>
<input list="browsers" id="myBrowser" name="myBrowser" /> <datalist id="browsers">

  <option value="Chrome">
  <option value="Firefox">
  <option value="Internet Explorer">
  <option value="Opera">
  <option value="Safari">
  <option value="Microsoft Edge">
</datalist>

```css
del {
  text-decoration: line-through;
  background-color: #fbb;
  color: #555;
}

ins {
  text-decoration: none;
  background-color: #d4fcbc;
}

blockquote {
  padding-left: 15px;
  border-left: 3px solid #d7d7db;
  font-size: 1rem;
}
```

<details>
    <summary>Details</summary>
    Something small enough to escape casual notice.
</details>

```css
details {
  border: 1px solid #aaa;
  border-radius: 4px;
  padding: 0.5em 0.5em 0;
}

summary {
  font-weight: bold;
  margin: -0.5em -0.5em 0;
  padding: 0.5em;
}

details[open] {
  padding: 0.5em;
}

details[open] summary {
  border-bottom: 1px solid #aaa;
  margin-bottom: 0.5em;
}
```
