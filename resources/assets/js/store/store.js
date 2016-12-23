import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex);

const state = {
    /* FLAGS TO BE CLOSED WHEN WHOLE WINDOW IS CLOSED */
    /* on/off if sidebar is opened */
    show_sidebar: false,
    /* we can be either in all conversations mode or
     active conversation mode */
    show_conversation: false,
    /* we can't be in coversation mode if there is no id*/
    conversation_id: null,
    /* if you want to open modal or whatever this needs to be true */
    show_add_people_to_conversation: false,
    /* END OF FLAGS */
    conversations: [],
    currentUser: {
      id: '',
      email: ''
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
    TOGGLE_SHOW_ADD_PEOPLE_TO_CONVERSATION ( state ) {
      state.show_add_people_to_conversation = ! state.show_add_people_to_conversation
    },
    ADD_USER_TO_CURRENT_CONVERSATION ( state, user ) {
      state.conversations.find( conversation => conversation.id === state.conversation_id ).participants.push(user)
    },
    REMOVE_CONVERSATION ( state, conversation ) {
      // TODO: below this store commented out there is a solution
    },
    ARCHIVE_CONVERSATION ( state, conversation ) {
      // TODO
    },
    UPADTE_ACTIVE_CONVERSATION_FEED ( state, message ) {
      // TODO
    }
}

const actions = {
    addConversation ( { commit }, conversation ) {
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
      console.log("inside action closeConversation")
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
    toggleShowAddPeopleToConversation ( {commit} ) {
      commit ( 'TOGGLE_SHOW_ADD_PEOPLE_TO_CONVERSATION' )
    },
    addUserToCurrentConversation ( { commit }, user ) {
      commit ( 'ADD_USER_TO_CURRENT_CONVERSATION', user )
    },
    testing( { commit } ) {
      Vue.$http.get('/test').then((response)=>{
        console.log(response.body)
      }).catch();
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
  }

}

export default new Vuex.Store({
  state,
  mutations,
  actions,
  getters
});
