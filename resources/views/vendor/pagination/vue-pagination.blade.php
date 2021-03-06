<nav >
    <ul class="pagination">
        <li v-if="pagination.current_page > 1">
            <a href="#" aria-label="Previous"
               v-on:click="changePage(pagination.current_page - 1)">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <li v-for="page in pagesNumber"
            v-bind:class="[ page == isActived ? 'active' : '']">
            <a href="#"
               v-on:click="changePage(page)">@{{ page }}</a>
        </li>
        <li v-if="pagination.current_page < pagination.last_page">
            <a href="#" aria-label="Next"
               v-on:click="changePage(pagination.current_page + 1)">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>