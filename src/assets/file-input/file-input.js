;(function ($, window, document, undefined) {

  $.fn.fileInput = function (options) {
    const settings = $.extend({}, options);

    return this.each(function () {
      const container = this;
      const hasFiles = ({ dataTransfer: { types = [] } }) => types.indexOf("Files") > -1;

      let FILES = {}, counter = 0;

      const form = container.closest("form");
      const template = container.querySelector(".template");
      const empty = container.querySelector(".empty");
      const gallery = container.querySelector(".gallery");
      const overlay = container.querySelector(".overlay");
      const hiddenInput = container.querySelector("input[type=file]");
      const dragArea = container.querySelector("article");

      container.querySelector("article").addEventListener("drop", dropHandler, false);
      container.querySelector("article").addEventListener("dragover", dragOverHandler, false);
      container.querySelector("article").addEventListener("dragleave", dragLeaveHandler, false);
      container.querySelector("article").addEventListener("dragenter", dragEnterHandler, false);
      container.querySelector("button").addEventListener("click", (e) => hiddenInput.click(e), false);
      // todo: is it needed?
      // container.querySelector(".cancel").onclick = () => {
      //   while (gallery.children.length > 0) {
      //     gallery.lastChild.remove();
      //   }
      //   FILES = {};
      //   empty.classList.remove("hidden");
      //   gallery.append(empty);
      //   todo: remove all files from input file buffer!!!
      // };

      hiddenInput.onchange = (e) => {
        for (const file of e.target.files) {
          addFile(file);
        }
      };

      gallery.onclick = ({ target }) => {
        if (target.classList.contains("delete")) {
          const ou = target.dataset.target;
          const index = Array.from(gallery.children).findIndex((el) => el.id === ou);
          document.getElementById(ou).remove(ou);
          gallery.children.length === 1 && empty.classList.remove("hidden");
          delete FILES[ou];
          removeFile(index);
        }
      };

      function validate() {
        setTimeout(() => {
          $(form).yiiActiveForm("validateAttribute", hiddenInput.id);
        }, 200);
      }

      function removeFile(index) {
        const attachments = hiddenInput.files;
        const fileBuffer = new DataTransfer();

        // append the file list to an array iteratively
        for (let i = 0; i < attachments.length; i++) {
          // Exclude file in specified index
          if (index !== i)
            fileBuffer.items.add(attachments[i]);
        }

        // Assign buffer to file input
        hiddenInput.files = fileBuffer.files;
        validate();
      }

      function addFile(file) {
        const isImage = file.type.match("image.*"), objectURL = URL.createObjectURL(file);
        const clone = template.content.cloneNode(true);

        clone.querySelector("h1").textContent = file.name;
        clone.querySelector("li").id = objectURL;
        clone.querySelector(".delete").dataset.target = objectURL;
        if (file.size > 1024) {
          clone.querySelector(".size").textContent = file.size > 1048576 ? Math.round(file.size / 1048576) + "mb" : Math.round(file.size / 1024) + "kb";
        } else {
          clone.querySelector(".size").textContent = file.size + "b";
        }
        if (isImage) {
          Object.assign(clone.querySelector("img"), {
            src: objectURL,
            alt: file.name,
          });
        } else {
          clone.querySelector("img").classList.add("hidden");
        }

        empty.classList.add("hidden");
        gallery.prepend(clone);

        FILES[objectURL] = file;
        validate();
      }

      function dropHandler(e) {
        e.preventDefault();
        hiddenInput.files = e.dataTransfer.files;
        for (const file of e.dataTransfer.files) {
          addFile(file);
          overlay.classList.remove("dragover");
          counter = 0;
        }
      }

      function dragEnterHandler(e) {
        e.preventDefault();
        if (!hasFiles(e)) {
          return;
        }
        ++counter && overlay.classList.add("dragover");
      }

      function dragLeaveHandler(e) {
        1 > --counter && overlay.classList.remove("dragover");
      }

      function dragOverHandler(e) {
        if (hasFiles(e)) {
          e.preventDefault();
        }
      }
    });
  };

})(jQuery, window, document);
