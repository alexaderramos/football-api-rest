<template>
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">All competitions
                            <b v-show="competitions.length != 0">{{ this.pagination.total }}</b>
                            <div v-show="loading" class="text-success">
                                <i class="fad fa-spinner fa-pulse"></i>
                                Wait a moment...
                            </div>
                            <div class="row pt-2">
                                <div class="col-md-2">
                                    <select v-model="selectPerPage" class="form-control" @change="changePage(1)">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                                <div class="col-md-5 offset-md-5">
                                    <input type="text" placeholder="Buscar..." class="form-control"
                                           v-model.trim="search" @keyup="onKeySearch">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <span style="font-size: 11px;font-style: italic">
                                    Info: <i style="color: #0d6efd" class="fa-solid fa-circle-info"></i> Show details&nbsp;&nbsp;&nbsp;&nbsp;<i
                                    style="color: #6a1a21"
                                    class="fa-regular fa-clipboard-list"></i> Show teams</span>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0" style="height: 400px;">
                            <table class="table  text-nowrap">
                                <thead class="table-head-fixed">
                                <tr>
                                    <th style="width: 30px">Code</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-if="searchInCompetitions.length">
                                    <tr v-for="(c, index) in searchInCompetitions">
                                        <td>{{ c.code }}</td>
                                        <td>{{ c.name }}</td>
                                        <td>{{ c.type }}</td>
                                        <td>
                                            <template v-if="competitionIndexLoading === index">
                                                <i class="fad fa-spinner fa-pulse"></i>
                                            </template>
                                            <template v-else>
                                                <a href="#!" @click="showDetails(index)">
                                                    <i style="color: #0d6efd"
                                                       class="fa-solid fa-circle-info"></i>
                                                </a>&nbsp;
                                            </template>

                                            <template v-if="competitionTeamIndexLoading === index">
                                                <i class="fad fa-spinner fa-pulse"></i>
                                            </template>
                                            <template v-else>
                                                <a href="#!" @click="showTeams(index)">
                                                    <i style="color: #6a1a21"
                                                       class="fa-regular fa-clipboard-list"></i>
                                                </a>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            No results
                                        </td>
                                    </tr>
                                </template>
                                </tbody>


                            </table>
                        </div>
                        <div class="card-footer">
                            <nav aria-label="Page navigation" class="float-end">
                                <ul class="pagination">
                                    <!--Botones anteriores-->
                                    <li v-if="pagination.currentPage == 1" class="page-item disabled">
                                        <a class="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true"><i class="fad fa-fast-backward"></i></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                    </li>
                                    <li v-else class="page-item">
                                        <a class="page-link" href="#" aria-label="Previous"
                                           @click.prevent="changePage(1)">
                                            <span aria-hidden="true"><i class="fad fa-fast-backward"></i></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                    </li>


                                    <li v-if="pagination.currentPage == 1" class="page-item disabled">
                                        <a class="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true"><i class="fad fa-step-backward"></i></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                    </li>
                                    <li v-else class="page-item">
                                        <a class="page-link" href="#" aria-label="Previous"
                                           @click.prevent="changePage(pagination.currentPage - 1)">
                                            <span aria-hidden="true"><i class="fad fa-step-backward"></i></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                    </li>
                                    <!--Botones anteriores-->

                                    <li v-for="(page,index) in getLinksPages" class="page-item"
                                        :class="[page === isCurrentPage ? 'active':'' ]">
                                        <span v-if="page === isCurrentPage" class="page-link">{{ page }}</span>
                                        <a v-else class="page-link" href="#"
                                           @click.prevent="changePage(page)">{{ page }}</a>
                                    </li>

                                    <!--Botones posteriores-->
                                    <li v-if="pagination.currentPage == pagination.lastPage" class="page-item disabled">
                                        <a class="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true"><i class="fad fa-step-forward"></i></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>
                                    <li v-else class="page-item">
                                        <a class="page-link" href="#" aria-label="Next"
                                           @click.prevent="changePage(pagination.currentPage + 1)">
                                            <span aria-hidden="true"><i class="fad fa-step-forward"></i></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>

                                    <li v-if="pagination.currentPage == pagination.lastPage" class="page-item disabled">
                                        <a class="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true"><i class="fad fa-fast-forward"></i></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>
                                    <li v-else class="page-item">
                                        <a class="page-link" href="#" aria-label="Next"
                                           @click.prevent="changePage(pagination.lastPage)">
                                            <span aria-hidden="true"><i class="fad fa-fast-forward"></i></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>
                                    <!--Botones posteriores-->
                                </ul>
                            </nav>
                            <div class="float-right">
                                <button type="button" @click="getCompetitions" class="btn btn-outline-success"><i
                                    class="fad fa-sync-alt"></i> Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <b-modal v-model="modalDetails" hide-footer>
            <template #modal-title>
                <template v-if="competition !=null"> {{ competition.name }}</template>
            </template>
            <div v-if="competition != null" class="d-block text-center">
                <table class="table">
                    <thead>
                    <tr>
                        <th colspan="2">Area</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Code</td>
                        <td>{{ competition.area.code }}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>{{ competition.area.name }}</td>
                    </tr>
                    <tr>
                        <td>Flag</td>
                        <td><img style="width: 30px" :src="competition.area.flag"/></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <b-button class="mt-3" block @click="closeModal('modalDetails')">Close</b-button>
        </b-modal>


        <b-modal v-model="modalTeams" hide-footer>
            <template #modal-title>
                <template v-if="teams !=null"> Teams
                </template>
            </template>
            <div v-if="teams != null" class="d-block text-center">
                <table class="table">
                    <thead>
                    <tr>
                        <th colspan="3">Teams</th>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>Crest</th>
                        <th>Players</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(t, index) in teams">
                        <td>{{ t.name }}</td>
                        <td><img style="width: 30px" :src="t.crest"></td>
                        <td><a href="#!" @click="showPlayers(index)"><i style="color: #6610f2"
                                                                        class="fa-solid fa-people-group"></i></a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <b-button class="mt-3" block @click="closeModal('modalTeams')">Close</b-button>
        </b-modal>

        <b-modal v-model="modalPlayers" size="lg" hide-footer>
            <template #modal-title>
                <template v-if="team !=null">{{ team.name }}&nbsp;players<img style="width: 30px;" :src="team.crest">
                </template>
            </template>
            <div v-if="team != null" class="d-block text-center">
                <div class="container-card">
                    <div v-for="p in team.players" class="card-report">
                        <div class="text-center container-img ">
                            <img
                                src="https://www.kindpng.com/picc/m/24-248253_user-profile-default-image-png-clipart-png-download.png">
                        </div>
                        <div class="title text-center">{{ p.name }}</div>
                    </div>
                </div>
            </div>
            <b-button class="mt-3" block @click="closeModal('modalPlayers')">Close</b-button>
        </b-modal>


    </div>
</template>

<script>
export default {
    name: "ViewTableComponent",
    data: () => ({
        competitionIndexLoading: -1,
        competitionTeamIndexLoading: -1,
        playersIndexLoading: -1,
        competitions: [],
        teams: [],
        competition: null,
        team: null,
        selectPerPage: 10,
        search: '',
        pagination: {
            perPage: 10,
            total: 0,
            currentPage: 1,
            from: 0,
            to: 0,
            lastPage: 0,
            currentUsers: [],
            links: []
        },
        loading: false,
        load_details: false,
        modalDetails: false,
        modalTeams: false,
        modalPlayers: false,


    }),
    created() {
        this.getCompetitions();

    },
    computed: {
        getCurrentCompetitions() {
            this.pagination.from = (this.pagination.currentPage - 1) * this.pagination.perPage;
            this.pagination.to = Number(this.pagination.from) + Number(this.pagination.perPage);
            return this.competitions.slice(this.pagination.from, this.pagination.to);
        },
        isCurrentPage() {
            return this.pagination.currentPage;
        },
        searchInCompetitions() {
            if (this.search.length > 0) {
                this.pagination.from = (this.pagination.currentPage - 1) * this.pagination.perPage;
                this.pagination.to = Number(this.pagination.from) + Number(this.pagination.perPage);
                this.pagination.total = this.competitions.filter((element) =>
                    this.checkEqual(element, 'code', this.search) ||
                    this.checkEqual(element, 'name', this.search) ||
                    this.checkEqual(element, 'type', this.search)
                ).length;
            } else {
                this.pagination.from = (this.pagination.currentPage - 1) * this.pagination.perPage;
                this.pagination.to = Number(this.pagination.from) + Number(this.pagination.perPage);
                this.pagination.total = this.competitions.length;
            }

            return this.competitions.filter((element) =>
                this.checkEqual(element, 'code', this.search) ||
                this.checkEqual(element, 'name', this.search) ||
                this.checkEqual(element, 'type', this.search)
            ).slice(this.pagination.from, this.pagination.to);

        },
        getLinksPages() {
            let cant = this.pagination.total / Number(this.pagination.perPage);
            this.pagination.links = [];
            let cumstom_links = [];
            /*obtiene los numeros de todas las paginas*/
            for (let i = 0; i < cant; i++) {
                this.pagination.links.push(i + 1);
            }
            this.pagination.lastPage = this.pagination.links.length;
            let start = 0;
            let limit = 5;
            if (this.pagination.currentPage < 3) {
                start = 0;
                return this.pagination.links.slice(start, limit);
            } else if (this.pagination.currentPage >= 3 && this.pagination.currentPage - 1 + 2 < this.pagination.lastPage) {
                start = (this.pagination.currentPage - 1) - 2;
                limit = start + limit;
                return this.pagination.links.slice(start, limit);
            } else {
                if (this.pagination.links.length == 4) {
                    start = 0;
                    limit = start + limit;
                    return this.pagination.links.slice(start, limit);
                } else {
                    start = (this.pagination.lastPage - 1) - 4;
                    limit = start + limit;
                    return this.pagination.links.slice(start, limit);
                }
            }

        }
    },
    methods: {
        checkEqual(element, key, search) {
            return element[key].toLowerCase().includes(search.toLowerCase())
        },
        getCompetitions() {
            this.loading = true;
            this.competitions = [];
            axios.get('/api/competitions')
                .then(response => {
                    if (!response.data.status) {
                        this.$toast.warning(response.data.message);
                    } else {
                        this.competitions = response.data.data;
                        for (let i = 0; i < this.competitions; i++) {
                            this.competition[i].load_details = false;
                        }
                        this.pagination.total = this.competitions.length;
                    }

                })
                .catch(error => {
                    console.log(error.response.data)
                    this.$toast.error(error.response.data.message);
                })
                .finally(() => {
                    this.loading = false;
                })
        },
        changePage(page) {
            this.pagination.perPage = this.selectPerPage;
            this.pagination.currentPage = Number(page);
        },
        onKeySearch() {
            this.changePage(1);
        },
        showDetails(index) {
            let c = this.searchInCompetitions[index]
            this.competitionIndexLoading = index;
            axios.get(`/api/competitions/${c.id}`)
                .then(response => {
                    if (!response.data.status) {
                        this.$toast.warning(response.data.message);
                    } else {
                        this.competition = response.data.data
                        this.modalDetails = true
                    }

                    // this.teams = response.data.data.teams
                })
                .catch((error) => {
                    this.$toast.error(error.response.data.message);
                    this.teams = []
                    this.competition = null
                    this.team = null
                })
                .finally(() => {

                    this.competitionIndexLoading = -1;
                })
        },
        showTeams(index) {
            let c = this.searchInCompetitions[index]
            this.competitionTeamIndexLoading = index;
            axios.get(`/api/competitions/${c.id}/teams`)
                .then(response => {
                    if (!response.data.status) {
                        this.$toast.warning(response.data.message);
                    } else {
                        this.modalTeams = true;
                        this.teams = response.data.data;
                    }
                })
                .catch(error => {
                    this.$toast.error(error.response.data.message);
                    this.teams = []
                })
                .finally(() => {
                    this.competitionTeamIndexLoading = -1;
                })

        },
        showPlayers(index) {
            this.team = this.teams[index];
            this.modalPlayers = true;
        },
        closeModal(modalModel) {
            switch (modalModel) {
                case 'modalDetails':
                    this.modalDetails = false;
                    break;
                case 'modalTeams':
                    this.modalTeams = false;
                    break;
                case 'modalPlayers':
                    this.modalPlayers = false;
                    break;
            }
        }
    },

}
</script>

<style scoped>
.table:not(.table-dark) {
    color: inherit;
}

.card-body > .table {
    margin-bottom: 0;
}

.text-nowrap {
    white-space: nowrap !important;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
    background-color: transparent;
}

table {
    border-collapse: collapse;
}

*, ::after, ::before {
    box-sizing: border-box;
}

.table-head-fixed {
    position: sticky;
    top: 0;
    background-color: #1a1e21;
    color: #fff;
}
</style>
