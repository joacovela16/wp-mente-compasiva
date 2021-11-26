<script>

    import moment from "moment";
    import FooterItem from "./FooterItem.svelte";
    import Icon from "./Icon.svelte";
</script>
<footer>
    <div class="bg-dark-700 w-full">
        <div class="container mx-auto flex flex-col md:(flex-row space-x-5 space-y-0) space-y-5 text-light-50  py-3">
            <FooterItem label="About us">
                It is a long established fact that a reader will be distracted by the readable content of a page when
                looking
                at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of
                letters,
                as opposed to using 'Content here, content here', making it look like readable English.
            </FooterItem>
            <FooterItem label="Cultivar la Mente">
                <div class="flex flex-col space-y-2">
                    <div>
                        <a href="#!">
                            <Icon label="Web site">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link"
                                     width="24"
                                     height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                                    <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                                </svg>
                            </Icon>
                        </a>
                    </div>
                    <div>
                        <a href="#!">
                            <Icon label="Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="icon icon-tabler icon-tabler-brand-facebook" width="24" height="24"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3"></path>
                                </svg>
                            </Icon>
                        </a>
                    </div>
                    <div>
                        <a href="#!">
                            <Icon label="Instagram">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="icon icon-tabler icon-tabler-brand-instagram" width="24" height="24"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <rect x="4" y="4" width="16" height="16" rx="4"></rect>
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <line x1="16.5" y1="7.5" x2="16.5" y2="7.501"></line>
                                </svg>
                            </Icon>
                        </a>
                    </div>
                </div>
            </FooterItem>
            <div class="flex-1"></div>
            <FooterItem label="Subscribe">
                <div class="flex flex-col space-y-2">
                    <div>
                        <input type="email" class="appearance-none rounded p-2" placeholder="Email">
                    </div>
                    <div>
                        <button type="submit"
                                class="p-2 appearance-none rounded bg-blue-500 text-white hover:(bg-white text-gray-700) transition-all">
                            Submit
                        </button>
                    </div>
                </div>
            </FooterItem>
        </div>
    </div>
    <div class="bg-dark-900 w-full p-3 text-light-50 flex flex-row">
        <div>© Copyright {moment().get("year")}</div>
        <div class="flex-1"></div>
        <!--        <div>Made by Bridge</div>-->
    </div>
</footer>