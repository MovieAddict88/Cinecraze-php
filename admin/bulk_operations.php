<div class="grid">
    <div class="card">
        <h2>📅 Year-based Bulk Generation</h2>
        <div class="form-group">
            <label>Content Type</label>
            <select id="bulk-type">
                <option value="movie">Movies</option>
                <option value="tv">TV Shows</option>
            </select>
        </div>
        <div class="form-group">
            <label>Year</label>
            <input type="number" id="bulk-year" min="1900" max="2030" value="2025">
        </div>
        <div class="form-group">
            <label>Number of Pages (1 page = 20 items)</label>
            <input type="number" id="bulk-pages" min="1" max="500" value="5">
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" id="bulk-skip-duplicates" checked>
                Skip Duplicates
            </label>
        </div>
        <button class="btn btn-primary" onclick="bulkGenerate()">
            <span class="loading" id="bulk-loading" style="display: none;"></span>
            Start Bulk Generation
        </button>
        <div class="progress-bar">
            <div class="progress-fill" id="bulk-progress"></div>
        </div>
        <div id="bulk-status"></div>
    </div>

    <div class="card">
        <h2>🎯 Genre-based Generation</h2>
        <div class="form-group">
            <label>Content Type</label>
            <select id="content-type-select">
                <option value="movie">Movies Only</option>
                <option value="tv">TV Series Only</option>
                <option value="both">Both Movies & TV Series</option>
            </select>
        </div>
        <div class="form-group">
            <label>Genre</label>
            <select id="genre-select">
                <optgroup label="Universal Genres">
                    <option value="28">Action</option>
                    <option value="12">Adventure</option>
                    <option value="16">Animation</option>
                    <option value="35">Comedy</option>
                    <option value="80">Crime</option>
                    <option value="99">Documentary</option>
                    <option value="18">Drama</option>
                    <option value="10751">Family</option>
                    <option value="14">Fantasy</option>
                    <option value="36">History</option>
                    <option value="27">Horror</option>
                    <option value="10402">Music</option>
                    <option value="9648">Mystery</option>
                    <option value="10749">Romance</option>
                    <option value="878">Science Fiction</option>
                    <option value="53">Thriller</option>
                    <option value="10752">War</option>
                    <option value="37">Western</option>
                </optgroup>
                <optgroup label="TV-Specific Genres">
                    <option value="10759">Action & Adventure</option>
                    <option value="16">Animation</option>
                    <option value="35">Comedy</option>
                    <option value="80">Crime</option>
                    <option value="99">Documentary</option>
                    <option value="18">Drama</option>
                    <option value="10751">Family</option>
                    <option value="10762">Kids</option>
                    <option value="9648">Mystery</option>
                    <option value="10763">News</option>
                    <option value="10764">Reality</option>
                    <option value="10765">Sci-Fi & Fantasy</option>
                    <option value="10766">Soap</option>
                    <option value="10767">Talk</option>
                    <option value="10768">War & Politics</option>
                    <option value="37">Western</option>
                </optgroup>
                <optgroup label="Movie-Specific Genres">
                    <option value="10770">TV Movie</option>
                </optgroup>
            </select>
        </div>
        <div class="form-group">
            <label>Release Year (Optional)</label>
            <select id="year-select">
                <option value="">Any Year</option>
                <option value="2025">2025 (Latest)</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
                <option value="2020">2020</option>
                <option value="2019">2019</option>
                <option value="2018">2018</option>
                <option value="2017">2017</option>
                <option value="2016">2016</option>
                <option value="2015">2015</option>
                <option value="2014">2014</option>
                <option value="2013">2013</option>
                <option value="2012">2012</option>
                <option value="2011">2011</option>
                <option value="2010">2010</option>
                <option value="2009">2009</option>
                <option value="2008">2008</option>
                <option value="2007">2007</option>
                <option value="2006">2006</option>
                <option value="2005">2005</option>
                <option value="2004">2004</option>
                <option value="2003">2003</option>
                <option value="2002">2002</option>
                <option value="2001">2001</option>
                <option value="2000">2000</option>
                <option value="1999">1999</option>
                <option value="1998">1998</option>
                <option value="1997">1997</option>
                <option value="1996">1996</option>
                <option value="1995">1995</option>
            </select>
        </div>
        <div class="form-group">
            <label>Number of Items</label>
            <input type="number" id="genre-count" min="1" max="10000" value="20">
        </div>
        <button class="btn btn-primary" onclick="generateByGenre()">
            <span class="loading" id="genre-loading" style="display: none;"></span>
            Generate by Genre
        </button>
        <button class="btn btn-secondary" onclick="testGeneration()" style="margin-top: 10px;">
            🧪 Test Generation (Debug)
        </button>
        <div id="genre-progress" style="margin-top: 15px; display: none;">
            <div class="progress-bar">
                <div class="progress-fill" id="genre-progress-fill"></div>
            </div>
            <div class="progress-text" id="genre-progress-text">Processing...</div>
        </div>
    </div>

    <div class="card">
        <h2>🌍 Regional Bulk Generation</h2>
        <div class="form-group">
            <label>Region</label>
            <select id="bulk-regional-select">
                <option value="hollywood">🎬 Hollywood</option>
                <option value="anime">🇯🇵 Anime</option>
                <option value="animation">🎨 Animation</option>
                <option value="kids">🧸 Kids / Family</option>
                <option value="kdrama">🇰🇷 K-Drama (Korean)</option>
                <option value="cdrama">🇨🇳 C-Drama (Chinese)</option>
                <option value="jdrama">🇯🇵 J-Drama (Japanese)</option>
                <option value="pinoy">🇵🇭 Pinoy Series (Filipino)</option>
                <option value="thai">🇹🇭 Thai Drama</option>
                <option value="indian">🇮🇳 Indian Series</option>
                <option value="turkish">🇹🇷 Turkish Drama</option>
                <option value="korean-variety">🎭 Korean Variety Shows</option>
            </select>
        </div>
        <div class="form-group">
            <label>Content Type</label>
            <select id="bulk-regional-content-type">
                <option value="both">🎭 Both Movies & Series</option>
                <option value="movie">🎬 Movies Only</option>
                <option value="tv">📺 TV Series Only</option>
            </select>
        </div>
        <div class="form-group">
            <label>Year or Range</label>
            <select id="bulk-regional-year-select">
                <option value="">Any Year (Most Popular)</option>
                <option value="2025">2025 (Latest)</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
                <option value="2020">2020</option>
                <option value="2019">2019</option>
                <option value="2018">2018</option>
                <option value="2017">2017</option>
                <option value="2016">2016</option>
                <option value="2015">2015</option>
                <option value="2014">2014</option>
                <option value="2013">2013</option>
                <option value="2012">2012</option>
                <option value="2011">2011</option>
                <option value="2010">2010</option>
                <option value="2009">2009</option>
                <option value="2008">2008</option>
                <option value="2007">2007</option>
                <option value="2006">2006</option>
                <option value="2005">2005</option>
                <option value="2004">2004</option>
                <option value="2003">2003</option>
                <option value="2002">2002</option>
                <option value="2001">2001</option>
                <option value="2000">2000</option>
                <option value="1999">1999</option>
                <option value="1998">1998</option>
                <option value="1997">1997</option>
                <option value="1996">1996</option>
                <option value="1995">1995</option>
                <option value="all-recent">All Recent (2020-2025)</option>
                <option value="all-2010s">All 2010s (2010-2019)</option>
                <option value="all-2000s">All 2000s (2000-2009)</option>
                <option value="all-classic">All Classic (1990-1999)</option>
                <option value="all-time">All Time (1990-2025)</option>
            </select>
        </div>
        <div class="form-group">
            <label>Number of Pages (1 page = 20 items)</label>
            <input type="number" id="bulk-regional-pages" min="1" max="500" value="5">
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" id="bulk-regional-skip-duplicates" checked>
                Skip Duplicates
            </label>
        </div>
        <button class="btn btn-primary" onclick="bulkGenerateRegional()">
            <span class="loading" id="bulk-regional-loading" style="display: none;"></span>
            Start Regional Generation
        </button>
        <div class="progress-bar" style="margin-top: 15px;">
            <div class="progress-fill" id="bulk-regional-progress"></div>
        </div>
        <div id="bulk-regional-status"></div>
    </div>
</div>
