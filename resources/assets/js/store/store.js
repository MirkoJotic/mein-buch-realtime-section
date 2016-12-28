import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex);

const state = {
    /* on/off if sidebar is opened */
    show_sidebar: '',
    /* we can be either in all conversation mode or
     one conversation mode */
    show_conversation: '',
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
      conversation.messages.push(data.message)
    },
    ADD_CONVERSATION ( state, conversation ) {
      state.conversations.push(conversation);
    },
    REMOVE_CONVERSATION ( state, conversation ) {
      // TODO: below this store commented out there is a solution
    },
    ARCHIVE_CONVERSATION ( state, conversation ) {
      // TODO
    },
    UPADTE_ACTIVE_CONVERSATION_FEED ( state, message ) {
      // TODO
    },
    ADD_USER_TO_CURRENT_CONVERSATION ( state, data ) {
      var conversation =  state.conversations.find( conversation => conversation.id === data.thread)
      conversation.participants.push(data.user)
    }
}

const actions = {
    addConversation ( { commit }, conversation ) {
      console.log(conversation)
      commit ( 'ADD_CONVERSATION', conversation )
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
    }
}

const getters = {
  currentConversation: state => {
    return state.conversations.find( conversation => conversation.id === state.conversation_id)
  },
  currentConversationParticipants: state => {
    return state.conversations.find( conversation => conversation.id === state.conversation_id).participants
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
