package support;

import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.test.context.junit4.SpringRunner;
import yoshino.WebApplication;
import yoshino.support.pili.PiliService;

import static org.junit.Assert.assertNotNull;

/**
 * Created by Volio on 2016/12/16.
 */
@RunWith(SpringRunner.class)
@SpringBootTest(classes = WebApplication.class)
public class PiliTest {

    private String streamKey;

    @Autowired
    private PiliService piliService;

    @Before
    public void generateStreamKey() {
        this.streamKey = "test";
    }

    @Test
    public void getPublishUrl() {
        assertNotNull(piliService.getPublishUrl(streamKey));
    }
}
