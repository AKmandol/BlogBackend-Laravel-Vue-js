<template>
  <div>
    <div class="content">
      <div class="container-fluid mt-3">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div
          class="
            _1adminOverveiw_table_recent
            _box_shadow
            _border_radious
            _mar_b30
            _p20
           
          "
        >
          <p class="_title14 ivu-mb _text_center">
            Create Blog Post    
          </p>

          <div class="_overflow _table_div  blogPost">
            
           <div class="_input_field">
             <Input maxlength="100" v-model="data.title" show-word-limit placeholder="Post Title..." />
           </div>    

           <div class="_input_field">
             <Upload
                :headers="{
                'x-csrf-token': token,
                'X-Requested-With': 'XMLHttpRequest',
                }"
                type="drag"
                action="/app/uploads"
                ref="uploads"
                :on-error="handleError"
                :on-success="handleSuccessBlog"
                :format="['jpg', 'jpeg', 'png']"
                :max-size="2048"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize">
                <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    <p>Click or drag to upload a title image</p>
                </div>
              </Upload>

               <div class="image_thumb" v-if="data.featuredImage">
                  <img class="img_thumb" :src="`${data.featuredImage}`" />
                  <span class="upload-list-cover">
                    <Icon type="ios-trash-outline" @click="deleteImage" />
                  </span>
               </div> 

            </div> 

             

           <div class="_input_field">

            <Select v-model="data.category_id" filterable multiple placeholder="Select Category" style="width:48% ;">
             <Option v-for="(c,i) in categoryLists" :value="c.id" :key="i">{{ c.categoryName }}</Option>
            </Select>

            <Select v-model="data.tag_id" filterable multiple placeholder="Select Tag" style="width:48% ;margin-left:4%">
             <Option v-for="(t,i) in tagLists" :value="t.id" :key="i">{{ t.tagName }}</Option>
            </Select>

           </div>     

           <div class="_input_field">
            <ckeditor class="editorSpace" :editor="editor" @ready="onReady" v-model="editorData" :config="editorConfig"></ckeditor>
           </div>

           <div class="_input_field">
            <Input v-model="data.post_excerpt"  maxlength="1000" show-word-limit type="textarea" placeholder="Enter Post Except..." />
           </div>

           <div class="_input_field">
            <Input prefix="ios-contact" v-model="data.metaDescription"  maxlength="1000" show-word-limit type="textarea" placeholder="Enter Meta Description..." />
           </div>

          </div>



          <div class="_text_center">
            <Button class="ivu-mt" 
            type="primary" 
            :loading="isCreating"
            :disabled="isCreating"
            @click="savingContent">
            <Icon type="md-send"/> {{isCreating ? 'Please Wait' :' Add Blog Post'}}
          </Button>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>

import DocumentEditor from '@ckeditor/ckeditor5-build-decoupled-document';

export default {

  data() {
    return {
    editor: DocumentEditor,
    editorData: '<p>Content of the editor.</p>',
    editorConfig: {
      ckfinder: {
        // uploadUrl: "{{ route('createBlog.upload', ['_token' => csrf_token()])}}",
        uploadUrl: 'app/createBlog/upload',
        
      },
      toolbar: {
        items: [
          'heading',
          '|',
          'fontSize',
          'fontFamily',
          'fontColor',
          'fontBackgroundColor',
          'imageResize',
          '|',
          'bold',
          'italic',
          'underline',
          'strikethrough',
          '|',
          'alignment',
          '|',
          'numberedList',
          'bulletedList',
          '|',
          'indent',
          'outdent',
          '|',
          'link',
          'blockQuote',
          'imageUpload',
          'insertTable',
          'mediaEmbed',
          '|',
          'undo',
          'redo',
        ]
      },
      language: 'cs',
      image: {
        toolbar: [
          'imageTextAlternative',
          'imageStyle:side',
        ]
      },
      table: {
        contentToolbar: [
          'tableColumn',
          'tableRow',
          'mergeTableCells'
        ]
      },
      licenseKey: ''
    },
    categoryLists: [],
    tagLists: [],
    isCreating: false,
    token: "",
    data: {
      title : '',
      featuredImage: '',
      post : '',
      post_excerpt : '',
      metaDescription : '',
      category_id: [],
      tag_id: [],
      jsonData: null,
    },
   }
  },

  methods: {
        async deleteImage() {
          let image;
            image = this.data.featuredImage;
            this.data.featuredImage = "";
            this.$refs.uploads.clearFiles();

          const res = await this.callApi("post", "app/delete_image", {
            imageName: image,
          });
          if (res.status != 200) {
            this.data.featuredImage = image;
            this.errorD();
          }
        },

    handleSuccessBlog(res, file) {
      res = `/uploads/${res}`;
      this.data.featuredImage = res;
    },

    onReady( editor )  {
        // Insert the toolbar before the editable area.
        editor.ui.getEditableElement().parentElement.insertBefore(
        editor.ui.view.toolbar.element,
        editor.ui.getEditableElement()
      )
    },
 
    async savingContent(response) {
      var data = response;
      // You have the content to save
      // console.log(this.editorData);
      // console.log(this.data);

      this.data.post = this.editorData;
      this.data.jsonData = JSON.stringify(data);

      if(this.data.post.trim() == '') 
      return this.info('Post field id required');

      if(this.data.title.trim() == '') 
      return this.info('Title field id required');

      if(this.data.post_excerpt.trim() == '') 
      return this.info('Post Excerpt field id required');

      if(this.data.metaDescription.trim() == '') 
      return this.info('Meta Description field id required');

      if(!this.data.category_id.length) 
      return this.info('Category Id field id required');

      if(!this.data.tag_id.length) 
      return this.info('Tag Id field id required');

      this.isCreating = true;
      const res = await this.callApi('post', 'app/create-blog', this.data);
      if(res.status === 200) {
        this.success('Data added Succesfully');
        this.$router.push('/blogs');
      }
      else {
        this.errorD();
      }
      this.isCreating = false;
    }

  },

  async created() {
    this.token = window.Laravel.csrfToken;
    const [cat, tag] = await Promise.all([
      this.callApi("get", "app/get_category"),
      this.callApi("get", "app/get_tags"),
    ])
    if (cat.status == 200) {
      this.categoryLists = cat.data
      this.tagLists = tag.data
    } else {
      this.errorD();
    }
  }






  
}
</script>

