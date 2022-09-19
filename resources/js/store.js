import { createStore } from "vuex";


export default createStore({
    state: {
        deleteModelObj : {
            showDeleteModal: false,
            deleteUrl: '',
            data: null,
            deletingIndex: -1,
            isDeleted: false,
        },
        user: false,
        userPermission: null,
    },

    getters: {
        getDeleteModalObj(state) {
            return state.deleteModelObj;
        },
        getUserPermission(state) {
            return state.userPermission;
        },
    },

    mutations: {
        setDeleteModal(state, data) {
            const deleteModelObjs = {
                showDeleteModal: false,
                deleteUrl: '',
                data: null,
                deletingIndex: -1,
                isDeleted: data,
            };

            state.deleteModelObj = deleteModelObjs;
        },

        setDeletingModalObj(state, data) {
            state.deleteModelObj = data;
        },
        setUpdateUSER(state, data) {
            state.user = data;
        },
        setUserPERMISSION(state, data) {
            state.userPermission = data;
        },
    },
});
