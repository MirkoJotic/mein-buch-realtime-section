<template>
  <div>
    <div class="sidebar-header">
        <div class="header-user online">
            <div class="user-info pull-left">
               New Conversation
            </div>
            <div class="user-info pull-right">
                <a class="close-chat">
                    <i class="icon ion-android-close"></i>
                </a>
            </div>
        </div>

        <active-conversation-add-people></active-conversation-add-people>

        <ul class="header-actions pull-right">
            <li>
                <a>
                    <i class="icon ion-ios-home-outline"></i>
                </a>
            </li>
        </ul>
    </div>

    <div  class="sidebar-body">
        <ul id="conversation-area" class="messages-list">
        </ul>
    </div>
  </div>
</template>

<script>
  export default {

  }
</script>
