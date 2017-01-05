import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex);

const state = {
    /* on/off if sidebar is opened */
    show_sidebar: false,
    /* we can be either in all conversation mode or
     one conversation mode */
    show_conversation: false,
    show_new_conversation: false,
    /* we can't be in coversation mode if there is no id*/
    conversation_id: null,
    conversations: [],
    currentUser: {
      id: null,
      email: null
    }
}

const mutations = {
    TOGGLE_SIDEBAR ( state ) {
      state.show_sidebar = ! state.show_sidebar
      state.show_conversation = false
      state.conversation_id = null
    },
    TOGGLE_NEW_CONVERSATION_COMPONENT ( state ) {
      state.show_new_conversation = ! state.show_new_conversation
    },
    SET_CONVERSATION_ID ( state, conversation_id ) {
      state.conversation_id = conversation_id
    },
    OPEN_CONVERSATION ( state ) {
      state.show_sidebar = true
      state.show_conversation = true
    },
    CLOSE_CONVERSATION ( state ) {
      state.show_conversation = false
    },
    SET_CONVERSATIONS ( state, conversations ) {
      state.conversations = conversations
    },
    SET_CURRENT_USER ( state, currentUser ) {
      state.currentUser = currentUser
    },
    ADD_MESSAGE_TO_CONVERSATION ( state, data ) {
      var conversation =  state.conversations.find( conversation => conversation.id === data.thread.id)
      var message = conversation.messages.find( msg => msg.id == data.message.id )
      if ( ! message )
        conversation.messages.push(data.message)
    },
    ADD_CONVERSATION ( state, conversation ) {
      var localConversation = state.conversations.find( c => c.id === conversation.id )
      if ( ! localConversation )
        state.conversations.push(conversation);
    },
    ADD_CONVERSATION_AND_SET_AS_ACTIVE ( state, conversation ) {
      var localConversation = state.conversations.find( c => c.id === conversation.id )
      if ( ! localConversation )
        state.conversations.push(conversation);

      state.conversation_id = conversation.id
    },
    REMOVE_CONVERSATION ( state, conversation ) {
      // TODO: below this store commented out there is a solution
    },
    ARCHIVE_CONVERSATION ( state, conversation ) {
      // TODO
    },
    ADD_USER_TO_CURRENT_CONVERSATION ( state, data ) {
      var conversation =  state.conversations.find( conversation => conversation.id === data.thread)
      conversation.participants.push(data.user)
    },
    MARK_ALL_MESSAGES_AS_SEEN ( state ) {
      var unseen = state.conversations.find( c => c.id === state.conversation_id ).unseen_messages
      unseen.splice(0)
    },
    ADD_UNSEEN_MESSAGES ( state, dataThread ) {
      var thread = state.conversations.find( c => c.id === dataThread.id )
      thread.unseen_messages = dataThread.unseen_messages
    }
}

const actions = {
    addConversation ( { commit }, conversation ) {
      console.log("INSIDE STORE ACTION addConversation")
      console.log(conversation)
      commit ( 'ADD_CONVERSATION', conversation )
    },
    addConversationAndSetAsActive ( { commit }, conversation ) {
      commit ( 'ADD_CONVERSATION_AND_SET_AS_ACTIVE', conversation )
    },
    openSidebar ( {commit} ) {
      commit ( 'TOGGLE_SIDEBAR' )
    },
    setConversationId ( {commit}, conversation_id) {
      commit ( 'SET_CONVERSATION_ID', conversation_id )
    },
    openConversation ( {commit} ) {
      commit ( 'OPEN_CONVERSATION' )
    },
    closeConversation ( {commit} ) {
      commit ( 'CLOSE_CONVERSATION')
    },
    toggleSidebar ( { commit } ) {
      commit ( 'TOGGLE_SIDEBAR' )
    },
    setConversations ( { commit }, conversations ) {
      commit ( 'SET_CONVERSATIONS', conversations )
    },
    setCurrentUser ( { commit }, currentUser ) {
      commit ( 'SET_CURRENT_USER', currentUser )
    },
    addMessage ( { commit }, data ) {
      commit ( 'ADD_MESSAGE_TO_CONVERSATION', data )
    },
    addUserToCurrentConversation ( { commit }, data ) {
      commit ( 'ADD_USER_TO_CURRENT_CONVERSATION', data )
    },
    markAllMessagesAsSeen ( { commit } ) {
      commit ( 'MARK_ALL_MESSAGES_AS_SEEN' )
    },
    addUnseenMessages ( {commit}, thread ) {
      commit ( 'ADD_UNSEEN_MESSAGES', thread )
    },
    toggleNewConversationComponent ( {commit} ) {
      commit ( 'TOGGLE_NEW_CONVERSATION_COMPONENT' )
    }
}

const getters = {
  currentConversation: state => {
    return state.conversations.find( conversation => conversation.id === state.conversation_id)
  },
  currentConversationMessages: state => {
    var cc = state.conversations.find( conversation => conversation.id === state.conversation_id)
    if ( cc )
      return cc.messages

    return []
  },
  currentConversationParticipants: state => {
    var cc = state.conversations.find( conversation => conversation.id === state.conversation_id)
    if ( cc )
      return cc.participants

    return []
  },
  conversations: state => {
    return state.conversations
  },
  currentUserId: state => { return state.currentUser.id },
  currentUserEmail: state => { return state.currentUser.email }

}

export default new Vuex.Store({
  state,
  mutations,
  actions,
  getters
});
