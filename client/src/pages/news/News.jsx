import './News.scss'
import loadingGif from './loading.gif';
import HeroBanner from '../../components/heroBanner/HeroBanner';

import pageHeaderProduct from '../../components/imgs/banner2.jpg'
import SearchForm from '../../components/searchForm/SearchForm';

import NewsCard from '../../components/news-card/NewsCard';
import ListTopProducts from '../../components/listTopProduct/ListTopProducts';
import { getAllProducts, getAllProductsStatus } from '../../store/productSlice';
import { fetchAsyncProducts } from '../../store/apiReq';
import { useDispatch, useSelector } from 'react-redux';
import React, { useState, useEffect } from 'react';

import Breadcrumbs from '@mui/material/Breadcrumbs';
import Link from '@mui/material/Link';
import { BASE_URL } from '../../utils/apiURL';
import axios from 'axios';

function News() {
    const dispatch = useDispatch();
    useEffect(() => {
        dispatch(fetchAsyncProducts());
    }, []);
    const products = useSelector(getAllProducts);

    const [listBlog, setListBlog] = useState([]);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        fetchBlog()
    }, []);
    const fetchBlog = async () => {
        await axios.get(`${BASE_URL}blog`, { withCredentials: true })
            .then(res => {
                setListBlog(res.data.data);
                setIsLoading(false);
            })
            .catch(err => {
                console.log(err);
                setListBlog([]);
                setIsLoading(false);
            })
    }

    return (
        <>
            <div className="container-news">
                <HeroBanner
                    title="#news"
                    summary="Brings you news about books and authors along with our picks for great reads!"
                    srcImg={pageHeaderProduct}
                />

                <Breadcrumbs aria-label="breadcrumb" sx={{ marginLeft: '40px', marginBottom: '30px', fontSize: '1.6rem',marginTop: '40px' }}>
                    <Link underline="hover" color="inherit" href="/">
                        Home
                    </Link>
                    <Link
                        underline="hover"
                        href="/material-ui/getting-started/installation/"
                        color="text.primary"
                    >
                        News
                    </Link>
                </Breadcrumbs>
                <div className='news-main-content'>

                    <div className="news">
                        {isLoading ? (
                            <img src={loadingGif} alt="loading" />
                            ) : (
                            listBlog.map((blog) => (
                                <NewsCard
                                key={blog.id}
                                id={blog.id}
                                title={blog.title}
                                banner_url={blog.banner_url}
                                publish_date={blog.publish_date}
                                />
                            ))
                        )}
                    </div>
                    <div className='sidebar-news'><ListTopProducts topProducts={products} /></div>
                </div>


            </div>
        </>
    );
}

export default News;
